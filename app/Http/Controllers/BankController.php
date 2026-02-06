<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::query()
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('code', 'like', '%'.$request->search.'%')
            )
            ->orderBy('name')
            ->paginate(10);

        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Bank::create($data);

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank created successfully.');
    }

    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $this->validated($request, $bank->id);

        $bank->update($data);

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank deleted successfully.');
    }

    private function validated(Request $request, $id = null): array
    {
        return $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:banks,code,' . $id,
            'status' => 'required|in:active,inactive',
        ]);
    }
}
