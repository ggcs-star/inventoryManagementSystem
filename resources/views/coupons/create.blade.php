@extends('layouts.admin')


@push('scripts')
<script>
    window.banks = @json($banks);
</script>
<script src="{{ asset('assets/js/admin/coupon.js') }}"></script>
@endpush
@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create Coupon</h2>
        <a href="{{ admin_route('coupons.index') }}" class="btn btn-secondary">
            ← Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('coupons.store') }}" method="POST">
        @csrf

        @include('coupons.partials.form')

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="submit" class="btn btn-primary px-4">
                Save Coupon
            </button>
        </div>
    </form>

</div>
@endsection
