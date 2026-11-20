<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StoreBarangMasukRequest;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Supplier;
use App\Services\StokService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BarangMasukController extends Controller
{
    public function index(Request $request): View
    {
        $query = BarangMasuk::with(['barang', 'supplier', 'user']);

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', fn ($barangQuery) => $barangQuery->where('nama_barang', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('nama', 'like', "%{$search}%"));
            });
        }

        $barangMasuks = $query->orderByDesc('tanggal_masuk')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('inventaris.barang_masuk.index', compact('barangMasuks'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('inventaris.barang_masuk.create', compact('suppliers', 'barangs'));
    }

    public function store(StoreBarangMasukRequest $request, StokService $stokService): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $stokService) {
            $barangMasuk = BarangMasuk::create([
                'barang_id' => $data['barang_id'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => auth()->id(),
                'jumlah' => $data['jumlah'],
                'harga_modal_satuan' => $data['harga_modal_satuan'] ?? null,
                'tanggal_masuk' => $data['tanggal_masuk'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            $stokService->tambahStok(
                (int) $data['barang_id'],
                (int) $data['jumlah'],
                (int) auth()->id(),
                $data['catatan'] ?? 'Barang masuk',
                $barangMasuk
            );
        });

        return redirect()
            ->route('inventaris.barang_masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat dan stok otomatis bertambah.');
    }
}