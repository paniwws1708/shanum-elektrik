<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StoreKategoriRequest;
use App\Http\Requests\Inventaris\UpdateKategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $kategoris = Kategori::orderBy('nama')->paginate(15);
        return view('inventaris.kategori.index', compact('kategoris'));
    }

    public function create(): View
    {
        return view('inventaris.kategori.create');
    }

    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        Kategori::create($request->validated());
        return redirect()->route('inventaris.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori): View
    {
        return view('inventaris.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori): RedirectResponse
    {
        $kategori->update($request->validated());
        return redirect()->route('inventaris.kategori.index')->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->barangs()->exists()) {
            return redirect()->route('inventaris.kategori.index')->with('error', 'Tidak dapat menghapus kategori yang masih dipakai barang.');
        }
        $kategori->delete();
        return redirect()->route('inventaris.kategori.index')->with('success', 'Kategori dihapus.');
    }
}
