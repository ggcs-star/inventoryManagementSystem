@extends('layouts.admin')

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon.js') }}"></script>
@endpush

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Coupons</h2>
        <a href="{{ admin_route('coupons.create') }}" class="btn btn-primary">
            + Add Coupon
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FILTER FORM --}}
    <form method="GET" class="card mb-4" id="couponFilterForm">
        <div class="card-body row g-2 align-items-end">

            {{-- Search --}}
            <div class="col-md-3">
                <div class="position-relative">
                    <input type="text"
                           name="search"
                           id="couponSearch"
                           class="form-control pe-5"
                           placeholder="Search coupon code"
                           value="{{ request('search') }}"
                           autocomplete="off">
                    <span id="clearCouponSearch"
                          class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                          style="cursor:pointer; display:none;">✕</span>
                </div>
            </div>

            {{-- Type --}}
            <div class="col-md-2">
                <select name="type" class="form-control auto-submit">
                    <option value="">All Types</option>
                    <option value="fixed" {{ request('type')=='fixed'?'selected':'' }}>Fixed</option>
                    <option value="percentage" {{ request('type')=='percentage'?'selected':'' }}>Percentage</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <select name="status" class="form-control auto-submit">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            {{-- Advanced Filter --}}
            <div class="col-md-2">
                <button type="button"
                        id="openCouponFilterSidebar"
                        class="btn btn-secondary w-100">
                    Filter
                </button>
            </div>

        </div>
    </form>

    {{-- BULK DELETE --}}
    <form method="POST"
          action="{{ admin_route('coupons.bulk-delete') }}"
          id="couponBulkDeleteForm">
        @csrf
        @method('DELETE')

        <button type="button"
                id="couponBulkDeleteBtn"
                class="btn btn-danger mb-3"
                disabled>
            Delete Selected
        </button>
    </form>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAllCoupons">
                        </th>
                        <th>#</th>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Platforms</th>
                        <th>Bank Offers</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coupons as $index => $coupon)
                    <tr>
                        <td>
                            <input type="checkbox"
                                   class="coupon-row-checkbox"
                                   name="ids[]"
                                   value="{{ $coupon->id }}"
                                   form="couponBulkDeleteForm">
                        </td>

                        <td>{{ $coupons->firstItem() + $index }}</td>

                        <td class="fw-semibold">{{ $coupon->code }}</td>

                        <td>
                            {{ $coupon->type === 'percentage'
                                ? $coupon->value.'%'
                                : '₹'.$coupon->value }}
                        </td>

                        <td>
                            @foreach($coupon->platforms as $platform)
                                <span class="badge bg-info">
                                    {{ $platform->display_name }}
                                </span>
                            @endforeach
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $coupon->bankOffers->count() }} offers
                            </span>
                        </td>

                        <td>
                            <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ admin_route('coupons.edit', $coupon) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ admin_route('coupons.destroy', $coupon) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this coupon?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No coupons found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $coupons->withQueryString()->links() }}
    </div>

</div>

{{-- ADVANCED FILTER SIDEBAR --}}
<div id="couponFilterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h5 class="mb-0">Advanced Filter</h5>
        <button type="button" id="closeCouponFilterSidebar">✕</button>
    </div>

    <div class="filter-sidebar-body">

        <div class="mb-3">
            <label class="form-label">Field</label>
            <select id="couponAdvField" class="form-control">
                <option value="code">Code</option>
                <option value="value">Discount</option>
                <option value="type">Type</option>
                <option value="is_active">Status</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Condition</label>
            <select id="couponAdvCondition" class="form-control">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equals</option>
                <option value=">">Greater Than</option>
                <option value="<">Less Than</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Value</label>
            <input type="text" id="couponAdvValue" class="form-control">
        </div>

        <button type="button"
                id="applyCouponAdvancedFilter"
                class="btn btn-primary w-100">
            Apply Filter
        </button>

    </div>
</div>
@endsection
