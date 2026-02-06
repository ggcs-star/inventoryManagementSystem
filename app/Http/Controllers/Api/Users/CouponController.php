<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Platform;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /* ==========================================================
       Helper: Resolve platform dynamically from request header
       ========================================================== */
    private function getPlatformId(): int
    {
        $platformKey = request()->header('X-Platform');

        if (!$platformKey) {
            abort(422, 'Platform header missing');
        }

        $platform = Platform::where('name', $platformKey)
            ->where('is_enabled', true)
            ->where('status', 'active')
            ->firstOrFail();

        return $platform->id;
    }

    /* ==========================================================
       GET /api/coupons
       List available coupons for user (platform-aware)
       ========================================================== */
    public function index(): JsonResponse
    {
        $platformId = $this->getPlatformId();

        $coupons = Coupon::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
           
            ->whereHas('platforms', function ($q) use ($platformId) {
                $q->where('platforms.id', $platformId);
            })

            ->withCount('bankOffers')
            ->get()
            ->map(fn ($c) => [
                'code'            => $c->code,
                'type'            => $c->type,
                'value'           => $c->value,
                'min_cart_amount' => $c->min_cart_amount,
                'max_discount'    => $c->max_discount,
                'has_bank_offers' => $c->bank_offers_count > 0,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $coupons,
        ]);
    }

    /* ==========================================================
       GET /api/coupons/{code}
       Single coupon details
       ========================================================== */
    public function show(string $code): JsonResponse
    {
        $platformId = $this->getPlatformId();

        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->whereHas('platforms', function ($q) use ($platformId) {
                $q->where('platforms.id', $platformId);
            })

            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'code'            => $coupon->code,
                'type'            => $coupon->type,
                'value'           => $coupon->value,
                'min_cart_amount' => $coupon->min_cart_amount,
                'max_discount'    => $coupon->max_discount,
                'starts_at'       => $coupon->starts_at,
                'expires_at'      => $coupon->expires_at,
                'has_bank_offers' => $coupon->bankOffers()->exists(),
            ],
        ]);
    }

    /* ==========================================================
       GET /api/coupons/{code}/banks
       Bank offers for coupon
       ========================================================== */
    public function banks(string $code): JsonResponse
    {
        $platformId = $this->getPlatformId();

        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
             ->whereHas('platforms', function ($q) use ($platformId) {
                $q->where('platforms.id', $platformId);
            })

            ->with(['bankOffers.bank'])
            ->firstOrFail();

        $banks = $coupon->bankOffers
            ->where('is_active', true)
            ->map(fn ($bo) => [
                'bank_id'      => $bo->bank_id,
                'bank_name'    => $bo->bank->name,
                'card_type'    => $bo->card_type,
                'type'         => $bo->type,
                'value'        => $bo->value,
                'max_discount' => $bo->max_discount,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $banks,
        ]);
    }

    /* ==========================================================
       POST /api/coupons/validate
       Validate coupon (no cart)
       ========================================================== */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $platformId = $this->getPlatformId();

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
             ->whereHas('platforms', function ($q) use ($platformId) {
                $q->where('platforms.id', $platformId);
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Invalid or expired coupon',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'valid'   => true,
            'message' => 'Coupon is valid',
            'has_bank_offers' => $coupon->bankOffers()->exists(),
        ]);
    }

    /* ==========================================================
       POST /api/coupons/validate-bank
       Validate bank offer for coupon
       ========================================================== */
    public function validateBank(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'bank_id'     => 'required|integer',
            'card_type'   => 'required|in:credit,debit,emi',
        ]);

        $platformId = $this->getPlatformId();

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
             ->whereHas('platforms', function ($q) use ($platformId) {
                $q->where('platforms.id', $platformId);
            })

            ->firstOrFail();

        $bankOffer = $coupon->bankOffers()
            ->where('bank_id', $request->bank_id)
            ->where('card_type', $request->card_type)
            ->where('is_active', true)
            ->first();

        if (!$bankOffer) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'No bank offer available',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'valid'   => true,
            'data' => [
                'discount_type'  => $bankOffer->type,
                'discount_value' => $bankOffer->value,
                'max_discount'   => $bankOffer->max_discount,
            ],
        ]);
    }
}
