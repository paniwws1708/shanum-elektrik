<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StoreBarangMasukRequest;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Services\StokService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BarangMasukController extends Controller
{
    /**
     * Menampilkan daftar transaksi barang masuk.
     */
    public function index(Request $request): View
    {
        $query = BarangMasuk::with([
            'barang',
            'supplier',
            'user',
        ]);

        // Pencarian berdasarkan nama barang atau supplier.
        $search = trim((string) $request->query('search'));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($barangQuery) use ($search) {
                    $barangQuery->where(
                        'nama_barang',
                        'like',
                        "%{$search}%"
                    );
                })->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                    $supplierQuery->where(
                        'nama',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        // Transaksi terbaru ditampilkan lebih dahulu.
        $barangMasuks = $query
            ->orderByDesc('tanggal_masuk')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'inventaris.barang_masuk.index',
            compact('barangMasuks')
        );
    }

    /**
     * Menampilkan form pencatatan barang masuk.
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('nama')->get();

        $barangs = Barang::orderBy('nama_barang')->get();

        return view(
            'inventaris.barang_masuk.create',
            compact('suppliers', 'barangs')
        );
    }

    /**
     * Menyimpan transaksi dan menambahkan stok barang.
     */
    public function store(
        StoreBarangMasukRequest $request,
        StokService $stokService
    ): RedirectResponse {
        $data = $request->validated();

        // Mengambil ID pengguna yang sedang login.
        $userId = (int) $request->user()->id;

        DB::transaction(function () use (
            $data,
            $userId,
            $stokService
        ) {
            // Menyimpan data transaksi barang masuk.
            $barangMasuk = BarangMasuk::create([
                'barang_id' => $data['barang_id'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => $userId,
                'jumlah' => $data['jumlah'],
                'harga_modal_satuan' => $data['harga_modal_satuan'] ?? null,
                'tanggal_masuk' => $data['tanggal_masuk'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            // Menambahkan stok dan mencatat riwayat perubahan.
            $stokService->tambahStok(
                (int) $data['barang_id'],
                (int) $data['jumlah'],
                $userId,
                $data['catatan'] ?? 'Barang masuk',
                $barangMasuk
            );
        });

        return redirect()
            ->route('inventaris.barang_masuk.index')
            ->with(
                'success',
                'Barang masuk berhasil dicatat dan stok otomatis bertambah.'
            );
    }
}
