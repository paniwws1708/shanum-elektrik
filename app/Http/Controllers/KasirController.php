<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * KasirController — menangani halaman POS, penyimpanan transaksi,
 * riwayat penjualan, dan detail struk.
 */
class KasirController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    //  POS — Halaman kasir untuk membuat transaksi baru
    // ═══════════════════════════════════════════════════════════════
    public function pos()
    {
        // Ambil barang yang stoknya > 0, beserta nama kategori
        $barangs = Barang::with('kategori')
            ->tersedia()           // scope: stok > 0
            ->orderBy('nama_barang')
            ->get()
            ->map(function ($b) {
                // Sertakan status_stok (aman/menipis) agar tampil di badge
                $b->status_stok_label = $b->status_stok;

                return $b;
            });

        // Daftar kategori untuk filter dropdown
        $kategoris = Kategori::orderBy('nama')->get();

        return view('kasir.pos', compact('barangs', 'kategoris'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  STORE — Simpan transaksi baru (dipanggil dari form POS)
    // ═══════════════════════════════════════════════════════════════
    public function store(Request $request, StokService $stokService)
    {
        // --- Validasi input dari form ---
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|integer|min:1',
            'bayar' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Keranjang tidak boleh kosong.',
            'items.min' => 'Keranjang tidak boleh kosong.',
            'items.*.barang_id.exists' => 'Barang tidak ditemukan.',
            'items.*.qty.min' => 'Jumlah minimal 1.',
            'bayar.required' => 'Masukkan jumlah uang pembayaran.',
        ]);

        // --- Proses di dalam DB::transaction agar atomik ---
        $transaksi = DB::transaction(function () use ($request, $stokService) {

            $items = $request->input('items');
            $noInvoice = Transaksi::buatNoInvoice();
            $totalHarga = 0;
            $detailData = [];

            // Hitung total dari harga di DATABASE (bukan dari browser)
            foreach ($items as $item) {
                $barang = Barang::lockForUpdate()->findOrFail($item['barang_id']);

                // Cek stok cukup
                if ($barang->stok < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok \"{$barang->nama_barang}\" tidak cukup (sisa: {$barang->stok}).",
                    ]);
                }

                $subtotal = $barang->harga_jual * $item['qty'];
                $totalHarga += $subtotal;

                $detailData[] = [
                    'barang' => $barang,
                    'nama_barang' => $barang->nama_barang,
                    'harga_satuan' => $barang->harga_jual,
                    'jumlah' => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            // Validasi pembayaran cukup
            $bayar = (float) $request->input('bayar');
            if ($bayar < $totalHarga) {
                throw ValidationException::withMessages([
                    'bayar' => 'Uang pembayaran kurang. Total: Rp '.number_format($totalHarga, 0, ',', '.'),
                ]);
            }

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'no_invoice' => $noInvoice,
                'user_id' => Auth::id(),
                'total_harga' => $totalHarga,
                'bayar' => $bayar,
                'kembalian' => $bayar - $totalHarga,
            ]);

            // Simpan detail & kurangi stok via StokService
            foreach ($detailData as $d) {
                $transaksi->details()->create([
                    'barang_id' => $d['barang']->id,
                    'nama_barang' => $d['nama_barang'],
                    'harga_satuan' => $d['harga_satuan'],
                    'jumlah' => $d['jumlah'],
                    'subtotal' => $d['subtotal'],
                ]);

                // Kurangi stok — StokService akan throw Exception jika negatif
                $stokService->kurangiStok(
                    $d['barang']->id,
                    $d['jumlah'],
                    Auth::id(),
                    "Penjualan {$noInvoice}",   // keterangan
                    $transaksi                   // referensi (morph)
                );
            }

            return $transaksi;
        });

        ActivityLog::catat(
            'Transaksi',
            'create',
            "Membuat transaksi {$transaksi->no_invoice} senilai Rp ".number_format($transaksi->total_harga, 0, ',', '.')
        );

        return redirect()
            ->route('kasir.show', $transaksi)
            ->with('success', "Transaksi {$transaksi->no_invoice} berhasil! Kembalian: Rp ".number_format($transaksi->kembalian, 0, ',', '.'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  RIWAYAT — Daftar transaksi (kasir lihat miliknya, admin semua)
    // ═══════════════════════════════════════════════════════════════
    public function riwayat(Request $request)
    {
        $query = Transaksi::with('details.barang.kategori', 'kasir')
            ->latest();

        // Kasir hanya lihat transaksinya sendiri; admin lihat semua
        if (Auth::user()->isKasir()) {
            $query->where('user_id', Auth::id());
        }

        // Pencarian berdasarkan no_invoice atau nama barang
        if ($search = $request->input('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                    ->orWhereHas('details', function ($dq) use ($search) {
                        $dq->where('nama_barang', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan kategori barang (lewat relasi details.barang)
        if ($kategoriId = $request->input('kategori_id')) {
            $query->whereHas('details.barang', function ($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        // Filter rentang tanggal
        if ($dariTanggal = $request->input('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $dariTanggal);
        }
        if ($sampaiTanggal = $request->input('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $sampaiTanggal);
        }

        $transaksis = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('kasir.riwayat', compact('transaksis', 'kategoris'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  SHOW — Detail / struk transaksi
    // ═══════════════════════════════════════════════════════════════
    public function show(Transaksi $transaksi)
    {
        // Kasir hanya boleh lihat transaksinya sendiri
        if (Auth::user()->isKasir() && $transaksi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat transaksi ini.');
        }

        $transaksi->load('details.barang', 'kasir');

        return view('kasir.show', compact('transaksi'));
    }
}
