@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h2>Banks</h2>
        <a href="{{ admin_route('banks.create') }}" class="btn btn-primary">+ Add Bank</a>
    </div>

    <form class="mb-3">
        <input name="search" class="form-control"
               placeholder="Search bank"
               value="{{ request('search') }}">
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Status</th>
                <th width="140">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $i => $bank)
                <tr>
                    <td>{{ $banks->firstItem() + $i }}</td>
                    <td>{{ $bank->name }}</td>
                    <td>{{ $bank->code }}</td>
                    <td>
                        <span class="badge {{ $bank->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($bank->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ admin_route('banks.edit', $bank) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form method="POST"
                              action="{{ admin_route('banks.destroy', $bank) }}"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete bank?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $banks->links() }}
</div>
@endsection
