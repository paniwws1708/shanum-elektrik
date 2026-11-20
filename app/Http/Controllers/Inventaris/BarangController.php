<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StoreBarangRequest;
use App\Http\Requests\Inventaris\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use App\Services\StokService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Daftar barang dengan pencarian & filter.
     */
    public function index(Request $request): View
    {
        $query = Barang::with('kategori');

        // pencarian nama / kode
        if ($search = $request->query('search')) {
            $query->cari($search);
        }

        // filter kategori
        if ($kategoriId = $request->query('kategori_id')) {
            $query->where('kategori_id', $kategoriId);
        }

        // filter status stok
        if ($status = $request->query('status')) {
            if ($status === 'menipis') {
                $query->stokMenipis();
            } elseif ($status === 'habis') {
                $query->stokHabis();
            } elseif ($status === 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimum');
            }
        }

        $barangs = $query->orderBy('nama_barang')
                         ->paginate(15)
                         ->withQueryString();

        $kategoris = Kategori::all();

        return view('inventaris.barang.index', compact('barangs', 'kategoris'));
    }

    /**
     * Form tambah barang.
     */
    public function create(): View
    {
        $kategoris = Kategori::all();

        return view('inventaris.barang.create', compact('kategoris'));
    }

    /**
     * Simpan barang baru (stok awal dicatat lewat StokService).
     */
    public function store(StoreBarangRequest $request, StokService $stokService): RedirectResponse
    {
        $data = $request->validated();

        // stok_minimum kosong -> pakai default 10 (kolom tidak boleh NULL)
        $data['stok_minimum'] = $data['stok_minimum'] ?? 10;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        // stok awal tidak disimpan langsung, dicatat lewat riwayat
        $awalStok = (int) $data['stok'];
        unset($data['stok']);

        DB::transaction(function () use ($data, $awalStok, $stokService) {
            $barang = Barang::create($data);

            if ($awalStok > 0) {
                $stokService->tambahStok($barang->id, $awalStok, auth()->id(), 'Stok awal barang');
            }
        });

        return redirect()->route('inventaris.barang.index')
                         ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Form edit barang.
     */
    public function edit(Barang $barang): View
    {
        $kategoris = Kategori::all();

        return view('inventaris.barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update barang (tidak mengubah stok langsung).
     */
    public function update(UpdateBarangRequest $request, Barang $barang): RedirectResponse
    {
        $data = $request->validated();

        $data['stok_minimum'] = $data['stok_minimum'] ?? $barang->stok_minimum;

        if ($request->hasFile('foto')) {
            // hapus foto lama bila ada
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('inventaris.barang.index')
                         ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang (soft delete, riwayat stok tetap aman).
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        $barang->delete();

        return redirect()->route('inventaris.barang.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }
}
