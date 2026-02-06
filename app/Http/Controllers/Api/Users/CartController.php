<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PlatformProduct;
use App\Models\PlatformPricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;
use App\Models\Platform;

use Throwable;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $cart = $this->getUserCart(true);

            if (!$cart || $cart->items->isEmpty()) {
                return $this->emptyCartResponse();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'cart_id' => $cart->id,
                    'items' => $cart->items->map(fn ($item) => $this->formatCartItem($item)),
                    'cart_total' => $this->cartTotal($cart),
                    'items_count' => $this->cartItemsCount($cart),
                ]
            ]);

        } catch (Throwable $e) {
            return $this->errorResponse('Get Cart API Error', $e);
        }
    }

    
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $platformProduct = $this->getPlatformProduct($request->product_id);
            $pricing = $this->getPricing(
                $platformProduct->id,
                $request->variant_id,
                $request->quantity
            );

            $cart = $this->getOrCreateCart();

            $cartItem = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'platform_id' => $platformProduct->platform_id,
            ]);

            $unitPrice = $pricing->final_price ?? $pricing->price;

            $cartItem->price = $unitPrice;
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $request->quantity;
            $cartItem->subtotal = $cartItem->quantity * $unitPrice;
            $cartItem->save();

            $cart->refresh();

            DB::commit();

            return $this->successCartResponse(
                'Item added to cart successfully',
                $cartItem,
                $cart
            );

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Add to Cart API Error', $e);
        }
    }

  
    public function update(Request $request, int $cartItemId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $cartItem = $this->getUserCartItem($cartItemId);

            $platformProduct = $this->getPlatformProduct(
                $cartItem->product_id,
                $cartItem->platform_id
            );

            $pricing = $this->getPricing(
                $platformProduct->id,
                $cartItem->product_variant_id,
                $request->quantity
            );

            $unitPrice = $pricing->final_price ?? $pricing->price;

            $cartItem->quantity = $request->quantity;
            $cartItem->price = $unitPrice;
            $cartItem->subtotal = $unitPrice * $request->quantity;
            $cartItem->save();

            $cart = $cartItem->cart;
            $cart->refresh();

            DB::commit();

            return $this->successCartResponse(
                'Cart item updated successfully',
                $cartItem,
                $cart
            );

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Update Cart Item API Error', $e);
        }
    }

    
    public function remove(int $cartItemId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $cartItem = $this->getUserCartItem($cartItemId);
            $cart = $cartItem->cart;

            $cartItem->delete();
            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'data' => [
                    'cart_total' => $this->cartTotal($cart),
                    'items_count' => $this->cartItemsCount($cart),
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Remove Cart Item API Error', $e);
        }
    }



    private function getUserCart(bool $withRelations = false): ?Cart
    {
        return Cart::where('user_id', auth()->id())
            ->when($withRelations, fn ($q) =>
                $q->with(['items.product:id,name,image_url', 'items.variant'])
            )
            ->first();
    }

    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    private function getUserCartItem(int $id): CartItem
    {
        return CartItem::where('id', $id)
            ->whereHas('cart', fn ($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();
    }

    private function getPlatformProduct(int $productId, ?int $platformId = null): PlatformProduct
    {
        return PlatformProduct::where('product_id', $productId)
            ->when($platformId, fn ($q) => $q->where('platform_id', $platformId))
            ->userVisible()
            ->firstOrFail();
    }

    private function getPricing(int $platformProductId, int $variantId, int $qty): PlatformPricing
    {
        $pricing = PlatformPricing::where([
            'platform_product_id' => $platformProductId,
            'product_variant_id' => $variantId,
            'status' => 'active',
        ])->firstOrFail();

        if ($pricing->quantity < $qty) {
            abort(422, 'Requested quantity not available');
        }

        return $pricing;
    }

    private function cartTotal(Cart $cart): float
    {
        return (float) $cart->items->sum('subtotal');
    }

    private function cartItemsCount(Cart $cart): int
    {
        return (int) $cart->items->sum('quantity');
    }
private function formatCartItem(CartItem $item): array
{
   
    $pricing = PlatformPricing::where([
            'platform_product_id' => $item->platform_id,
            'product_variant_id'  => $item->product_variant_id,
            'status' => 'active',
        ])
        ->with(['variant.variant', 'variant.value'])
        ->first();

    $type  = null;
    $value = null;

    if ($pricing && $pricing->variant) {
        $type  = $pricing->variant->variant?->name;
        $value = $pricing->variant->value?->value;
    }

    if (!$type || !$value) {
        $productVariant = $item->product
            ?->variants
            ?->firstWhere('id', $item->product_variant_id);

        if ($productVariant) {
            $type  = $productVariant->variant?->name;
            $value = $productVariant->value?->value;
        }
    }

    return [
        'id' => $item->id,
        'product_id' => $item->product_id,
        'variant_id' => $item->product_variant_id,
        'platform_id' => $item->platform_id,
        'product_name' => $item->product?->name,

        // ✅ NEVER NULL NOW (as long as product has variants)
        'variant' => [
            'type'  => $type,
            'value' => $value,
        ],

        'price' => $item->price,
        'quantity' => $item->quantity,
        'subtotal' => $item->subtotal,
        'image_url' => $item->product?->image_url,
    ];
}


    private function emptyCartResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cart_id' => null,
                'items' => [],
                'cart_total' => 0,
                'items_count' => 0,
            ]
        ]);
    }

    private function errorResponse(string $context, Throwable $e): JsonResponse
    {
        Log::error($context, [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again later.'
        ], 500);
    }

    private function successCartResponse(string $msg, CartItem $item, Cart $cart): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg,
            'data' => [
                'cart_item' => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'platform_id' => $item->platform_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ],
                'cart_total' => $this->cartTotal($cart),
                'items_count' => $this->cartItemsCount($cart),
            ]
        ]);
    }


public function applyCoupon(Request $request): JsonResponse
{
    $request->validate([
        'coupon_code' => 'required|string',
        'bank_id'     => 'nullable|integer',
        'card_type'   => 'nullable|in:credit,debit,emi',
    ]);

    try {
        DB::beginTransaction();

        $cart = $this->getOrCreateCart();
        $cart->load('items');

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 422);
        }

        /* ---------- platform ---------- */
        $platformKey = $request->header('X-Platform');

        $platform = Platform::where('name', $platformKey)
            ->where('is_enabled', true)
            ->where('status', 'active')
            ->firstOrFail();

        /* ---------- cart total ---------- */
        $cartTotal = $this->cartTotal($cart);

        /* ---------- coupon ---------- */
        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->whereHas('platforms', fn ($q) =>
                $q->where('platforms.id', $platform->id)
            )
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon',
            ], 422);
        }

        if ($coupon->min_cart_amount && $cartTotal < $coupon->min_cart_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum cart amount not met',
            ], 422);
        }

        /* ---------- coupon discount ---------- */
        $couponDiscount = $coupon->type === 'fixed'
            ? $coupon->value
            : ($cartTotal * $coupon->value / 100);

        if ($coupon->max_discount) {
            $couponDiscount = min($couponDiscount, $coupon->max_discount);
        }

        /* ---------- bank discount (optional) ---------- */
        $bankDiscount = 0;

        if ($request->bank_id && $request->card_type) {
            $bankOffer = $coupon->bankOffers()
                ->where('bank_id', $request->bank_id)
                ->where('card_type', $request->card_type)
                ->where('is_active', true)
                ->first();

            if (!$bankOffer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid bank offer',
                ], 422);
            }

            $bankDiscount = $bankOffer->type === 'fixed'
                ? $bankOffer->value
                : ($cartTotal * $bankOffer->value / 100);

            if ($bankOffer->max_discount) {
                $bankDiscount = min($bankDiscount, $bankOffer->max_discount);
            }
        }

        /* ---------- save on cart (NO cart logic touched) ---------- */
        $cart->coupon_id       = $coupon->id;
        $cart->coupon_code     = $coupon->code;
        $cart->coupon_discount = round($couponDiscount, 2);
        $cart->bank_discount   = round($bankDiscount, 2);
        $cart->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => [
                'cart_total'     => $cartTotal,
                'coupon_discount'=> round($couponDiscount, 2),
                'bank_discount'  => round($bankDiscount, 2),
                'payable_amount' => max(
                    $cartTotal - ($couponDiscount + $bankDiscount),
                    0
                ),
            ],
        ]);

    } catch (Throwable $e) {
        DB::rollBack();
        return $this->errorResponse('Apply Coupon API Error', $e);
    }
}

public function removeCoupon(): JsonResponse
{
    try {
        DB::beginTransaction();

        $cart = $this->getOrCreateCart();

        $cart->coupon_id       = null;
        $cart->coupon_code     = null;
        $cart->coupon_discount= null;
        $cart->bank_discount  = null;
        $cart->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully',
            'data' => [
                'cart_total' => $this->cartTotal($cart),
                'items_count'=> $this->cartItemsCount($cart),
            ],
        ]);

    } catch (Throwable $e) {
        DB::rollBack();
        return $this->errorResponse('Remove Coupon API Error', $e);
    }
}
}