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
use Throwable;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang.
     */
    public function index(Request $request): View
    {
        $query = Barang::with('kategori');

        // Pencarian berdasarkan nama atau kode barang.
        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->cari($search);
        }

        // Filter berdasarkan kategori.
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->query('kategori_id'));
        }

        // Filter berdasarkan kondisi stok.
        $status = $request->query('status');

        if ($status === 'menipis') {
            $query->stokMenipis();
        } elseif ($status === 'habis') {
            $query->stokHabis();
        } elseif ($status === 'aman') {
            $query->whereColumn('stok', '>', 'stok_minimum');
        } else {
            $status = '';
        }

        // Mengurutkan barang berdasarkan nama.
        $barangs = $query
            ->orderBy('nama_barang')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        // Mengambil daftar kategori untuk filter.
        $kategoris = Kategori::orderBy('nama')->get();

        return view('inventaris.barang.index', compact(
            'barangs',
            'kategoris'
        ));
    }

    /**
     * Menampilkan form tambah barang.
     */
    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('inventaris.barang.create', compact('kategoris'));
    }

    /**
     * Menyimpan barang baru dan mencatat stok awal.
     */
    public function store(
        StoreBarangRequest $request,
        StokService $stokService
    ): RedirectResponse {
        $data = $request->validated();

        // Menggunakan nilai default jika stok minimum kosong.
        $data['stok_minimum'] = $data['stok_minimum'] ?? 10;

        $foto = null;

        try {
            // Menyimpan foto barang jika tersedia.
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto')->store('barang', 'public');

                if (!$foto) {
                    throw new \RuntimeException('Foto barang gagal disimpan.');
                }

                $data['foto'] = $foto;
            }

            // Stok awal dicatat melalui StokService.
            $stokAwal = (int) $data['stok'];
            unset($data['stok']);

            DB::transaction(function () use (
                $data,
                $stokAwal,
                $stokService
            ) {
                $barang = Barang::create($data);

                // Mencatat stok awal jika jumlahnya lebih dari nol.
                if ($stokAwal > 0) {
                    $stokService->tambahStok(
                        $barang->id,
                        $stokAwal,
                        (int) auth()->id(),
                        'Stok awal barang'
                    );
                }
            });
        } catch (Throwable $exception) {
            // Menghapus foto jika proses penyimpanan gagal.
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }

            throw $exception;
        }

        return redirect()
            ->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit barang.
     */
    public function edit(Barang $barang): View
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('inventaris.barang.edit', compact(
            'barang',
            'kategoris'
        ));
    }

    /**
     * Memperbarui data barang tanpa mengubah stok.
     */
    public function update(
        UpdateBarangRequest $request,
        Barang $barang
    ): RedirectResponse {
        $data = $request->validated();

        // Mempertahankan stok minimum lama jika input dikosongkan.
        $data['stok_minimum'] = $data['stok_minimum']
            ?? $barang->stok_minimum;

        $fotoLama = $barang->foto;
        $fotoBaru = null;

        try {
            // Menyimpan foto baru jika pengguna mengunggah foto.
            if ($request->hasFile('foto')) {
                $fotoBaru = $request->file('foto')
                    ->store('barang', 'public');

                if (!$fotoBaru) {
                    throw new \RuntimeException('Foto barang gagal disimpan.');
                }

                $data['foto'] = $fotoBaru;
            }

            // Memperbarui data barang dalam transaksi database.
            DB::transaction(function () use ($barang, $data) {
                $barang->update($data);
            });
        } catch (Throwable $exception) {
            // Menghapus foto baru jika proses update gagal.
            if ($fotoBaru) {
                Storage::disk('public')->delete($fotoBaru);
            }

            throw $exception;
        }

        // Menghapus foto lama setelah update berhasil.
        if (
            $fotoBaru &&
            $fotoLama &&
            Storage::disk('public')->exists($fotoLama)
        ) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang menggunakan soft delete.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        $barang->delete();

        return redirect()
            ->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
