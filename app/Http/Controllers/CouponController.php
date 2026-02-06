<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Platform;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::with(['platforms', 'bankOffers.bank'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%');
            })
            ->when($request->type, fn ($q) =>
                $q->where('type', $request->type)
            )
            ->when($request->status, fn ($q) =>
                $q->where('is_active', $request->status === 'active')
            )
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create', [
            'platforms' => Platform::where('is_enabled', true)->get(),
            'banks'     => Bank::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

  public function store(Request $request)
{
    $data = $this->validatedData($request);

    DB::transaction(function () use ($request, $data) {

        $coupon = Coupon::create($data);
        $coupon->platforms()->sync($request->platform_ids);

        foreach ($request->bank_offers ?? [] as $offer) {

    if (
        empty($offer['bank_id']) ||
        empty($offer['value']) ||
        empty($offer['type'])
    ) {
        continue;
    }

    $bankOffer = $coupon->bankOffers()->create([
        'bank_id'      => $offer['bank_id'],
        'card_type'    => $offer['card_type'],
        'type'         => $offer['type'],
        'value'        => $offer['value'],
        'max_discount' => $offer['max_discount'] ?? null,
        'starts_at'    => $offer['starts_at'] ?? null,
        'expires_at'   => $offer['expires_at'] ?? null,
        'is_active'    => $offer['is_active'] ?? true,
    ]);

}

    });

    return redirect()
        ->to(admin_route('coupons.index'))
        ->with('success', 'Coupon created successfully.');
}

    public function edit(Coupon $coupon)
    {
        $coupon->load('platforms', 'bankOffers.bank');

        return view('coupons.edit', [
            'coupon'    => $coupon,
            'platforms' => Platform::where('is_enabled', true)->get(),
            'banks'     => Bank::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

   
  public function update(Request $request, Coupon $coupon)
{
    $data = $this->validatedData($request, $coupon->id);

    DB::transaction(function () use ($request, $coupon, $data) {

        $coupon->update($data);

        // Coupon-level platforms
        $coupon->platforms()->sync($request->platform_ids);

        // Old bank offers remove
        $coupon->bankOffers()->delete();

        foreach ($request->bank_offers ?? [] as $offer) {

            if (
                empty($offer['bank_id']) ||
                empty($offer['value']) ||
                empty($offer['type'])
            ) {
                continue;
            }

            $coupon->bankOffers()->create([
                'bank_id'      => $offer['bank_id'],
                'card_type'    => $offer['card_type'],
                'type'         => $offer['type'],
                'value'        => $offer['value'],
                'max_discount' => $offer['max_discount'] ?? null,
                'starts_at'    => $offer['starts_at'] ?? null,
                'expires_at'   => $offer['expires_at'] ?? null,
                'is_active'    => $offer['is_active'] ?? true,
            ]);
        }
    });

    return redirect()
        ->to(admin_route('coupons.index'))
        ->with('success', 'Coupon updated successfully.');
}


    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()
            ->to(admin_route('coupons.index'))
            ->with('success', 'Coupon deleted successfully.');
    }

   public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:coupons,id',
    ]);

    Coupon::whereIn('id', $request->ids)->delete();

    return redirect()
        ->to(admin_route('coupons.index'))
        ->with('success', 'Selected coupons deleted successfully.');
}


    private function validatedData(Request $request, $couponId = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $couponId,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:1',

            'min_cart_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',

            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',

            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',

            'platform_ids' => 'required|array',
            'platform_ids.*' => 'exists:platforms,id',

            'bank_offers' => 'nullable|array',
           

'bank_offers.*.bank_id' =>
    'required_with:bank_offers.*.value|exists:banks,id',

'bank_offers.*.card_type' =>
    'required_with:bank_offers.*.value|in:credit,debit,emi',

'bank_offers.*.type' =>
    'required_with:bank_offers.*.value|in:fixed,percentage',

'bank_offers.*.value' =>
    'required_with:bank_offers.*.bank_id|numeric|min:1',

            'bank_offers.*.max_discount' => 'nullable|numeric|min:0',
            'bank_offers.*.is_active' => 'boolean',
            'bank_offers.*.starts_at' => 'nullable|date',
            'bank_offers.*.expires_at' => 'nullable|date|after:bank_offers.*.starts_at',
        ]);
    }
}
