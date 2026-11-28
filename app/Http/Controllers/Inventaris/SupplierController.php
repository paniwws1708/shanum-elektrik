<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StoreSupplierRequest;
use App\Http\Requests\Inventaris\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * List supplier with pagination.
     */
    public function index(): View
    {
        $suppliers = Supplier::orderBy('nama')->paginate(15);

        return view('inventaris.supplier.index', compact('suppliers'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('inventaris.supplier.create');
    }

    /**
     * Store new supplier.
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        Supplier::create($request->validated());

        return redirect()->route('inventaris.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit(Supplier $supplier): View
    {
        return view('inventaris.supplier.edit', compact('supplier'));
    }

    /**
     * Update supplier.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()->route('inventaris.supplier.index')
            ->with('success', 'Supplier berhasil diubah.');
    }

    /**
     * Soft‑delete supplier.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('inventaris.supplier.index')
            ->with('success', 'Supplier dihapus.');
    }
}
