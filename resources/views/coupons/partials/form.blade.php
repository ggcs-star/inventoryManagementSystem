{{-- ================= COUPON DETAILS ================= --}}
<div class="card mb-4">
    <div class="card-header"><strong>Coupon Details</strong></div>

    <div class="card-body row">

        <div class="col-md-3 mb-3">
            <label class="form-label">Coupon Code *</label>
            <input type="text"
                   name="code"
                   class="form-control"
                   value="{{ old('code', $coupon->code ?? '') }}"
                   {{ isset($coupon) ? 'readonly' : '' }}>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Discount Type *</label>
            <select name="type" class="form-control">
                <option value="fixed" {{ old('type', $coupon->type ?? '')=='fixed'?'selected':'' }}>Fixed</option>
                <option value="percentage" {{ old('type', $coupon->type ?? '')=='percentage'?'selected':'' }}>Percentage</option>
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Discount Value *</label>
            <input type="number" step="0.01"
                   name="value"
                   class="form-control"
                   value="{{ old('value', $coupon->value ?? '') }}">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Max Discount</label>
            <input type="number" step="0.01"
                   name="max_discount"
                   class="form-control"
                   value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Minimum Cart Amount</label>
            <input type="number" step="0.01"
                   name="min_cart_amount"
                   class="form-control"
                   value="{{ old('min_cart_amount', $coupon->min_cart_amount ?? '') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Usage Limit</label>
            <input type="number"
                   name="usage_limit"
                   class="form-control"
                   value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
        </div>

        <div class="col-md-4 mb-3 d-flex align-items-end">
            <div class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Coupon Start Date</label>
            <input type="datetime-local"
                   name="starts_at"
                   class="form-control"
                   value="{{ old('starts_at', isset($coupon?->starts_at) ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Coupon End Date</label>
            <input type="datetime-local"
                   name="expires_at"
                   class="form-control"
                   value="{{ old('expires_at', isset($coupon?->expires_at) ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
        </div>

    </div>
</div>

{{-- ================= PLATFORMS ================= --}}
<div class="card mb-4">
    <div class="card-header"><strong>Platforms</strong></div>
    <div class="card-body">
        @foreach($platforms as $platform)
            <label class="me-4">
                <input type="checkbox"
                       name="platform_ids[]"
                       value="{{ $platform->id }}"
                       {{ isset($coupon) && $coupon->platforms->contains($platform->id) ? 'checked' : '' }}>
                {{ $platform->display_name }}
            </label>
        @endforeach
    </div>
</div>

{{-- ================= BANK OFFERS ================= --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Bank Offers</strong>
        <button type="button"
                class="btn btn-sm btn-outline-primary"
                id="addBankOfferBtn">
            + Add Bank Offer
        </button>
    </div>

    <div class="card-body" id="bankOffersWrapper">

        @php
            $bankOffers = old('bank_offers')
                ?? (isset($coupon) ? $coupon->bankOffers->toArray() : [ [] ]);
        @endphp

        @foreach($bankOffers as $i => $bo)
            <div class="border rounded p-3 mb-3 bank-offer-row">

                <div class="row">

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Bank *</label>
                        <select name="bank_offers[{{ $i }}][bank_id]" class="form-control">
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ ($bo['bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Card Type *</label>
                        <select name="bank_offers[{{ $i }}][card_type]" class="form-control">
                            <option value="">Select</option>
                            <option value="credit" {{ ($bo['card_type'] ?? '')=='credit'?'selected':'' }}>Credit</option>
                            <option value="debit" {{ ($bo['card_type'] ?? '')=='debit'?'selected':'' }}>Debit</option>
                            <option value="emi" {{ ($bo['card_type'] ?? '')=='emi'?'selected':'' }}>EMI</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Discount Type *</label>
                        <select name="bank_offers[{{ $i }}][type]" class="form-control">
                            <option value="">Select</option>
                            <option value="fixed" {{ ($bo['type'] ?? '')=='fixed'?'selected':'' }}>Fixed</option>
                            <option value="percentage" {{ ($bo['type'] ?? '')=='percentage'?'selected':'' }}>Percentage</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Discount *</label>
                        <input type="number"
                               step="0.01"
                               name="bank_offers[{{ $i }}][value]"
                               class="form-control"
                               value="{{ $bo['value'] ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Offer Start</label>
                        <input type="datetime-local"
                               name="bank_offers[{{ $i }}][starts_at]"
                               class="form-control"
                               value="{{ isset($bo['starts_at']) ? \Carbon\Carbon::parse($bo['starts_at'])->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Offer End</label>
                        <input type="datetime-local"
                               name="bank_offers[{{ $i }}][expires_at]"
                               class="form-control"
                               value="{{ isset($bo['expires_at']) ? \Carbon\Carbon::parse($bo['expires_at'])->format('Y-m-d\TH:i') : '' }}">
                    </div>

                </div>

            </div>
        @endforeach

    </div>
</div>



