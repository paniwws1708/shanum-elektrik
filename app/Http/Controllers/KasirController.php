<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Services\StokService;
use App\Http\Requests\StoreTransaksiRequest;
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
    private const PER_HALAMAN = 10;
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
    public function store(StoreTransaksiRequest $request, StokService $stokService)
    {


        // --- Proses di dalam DB::transaction agar atomik ---
        $transaksi = DB::transaction(function () use ($request, $stokService) {

            $items     = $request->input('items');
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
                    'barang'       => $barang,
                    'nama_barang'  => $barang->nama_barang,
                    'harga_satuan' => $barang->harga_jual,
                    'jumlah'       => $item['qty'],
                    'subtotal'     => $subtotal,
                ];
            }

            // Validasi pembayaran cukup
            $bayar = (float) $request->input('bayar');
            if ($bayar < $totalHarga) {
                throw ValidationException::withMessages([
                    'bayar' => 'Uang pembayaran kurang. Total: Rp ' . number_format($totalHarga, 0, ',', '.'),
                ]);
            }

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'no_invoice'  => $noInvoice,
                'user_id'     => Auth::id(),
                'total_harga' => $totalHarga,
                'bayar'       => $bayar,
                'kembalian'   => $bayar - $totalHarga,
            ]);

            // Simpan detail & kurangi stok via StokService
            foreach ($detailData as $d) {
                $transaksi->details()->create([
                    'barang_id'    => $d['barang']->id,
                    'nama_barang'  => $d['nama_barang'],
                    'harga_satuan' => $d['harga_satuan'],
                    'jumlah'       => $d['jumlah'],
                    'subtotal'     => $d['subtotal'],
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

        return redirect()
            ->route('kasir.show', $transaksi)
            ->with('success', "Transaksi {$transaksi->no_invoice} berhasil! Kembalian: Rp " . number_format($transaksi->kembalian, 0, ',', '.'));
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

        // Pencarian berdasarkan no_invoice
        if ($search = $request->input('cari')) {
            $query->where('no_invoice', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori barang (lewat relasi details.barang)
        if ($kategoriId = $request->input('kategori_id')) {
            $query->whereHas('details.barang', function ($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        $transaksis = $query->paginate(self::PER_HALAMAN)->withQueryString();
        $kategoris  = Kategori::orderBy('nama')->get();

        return view('kasir.riwayat', compact('transaksis', 'kategoris'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  SHOW — Detail / struk transaksi
    // ═══════════════════════════════════════════════════════════════
    public function show(Transaksi $transaksi)
    {
        // Kasir hanya boleh lihat transaksinya sendiri
        abort_if(
            Auth::user()->isKasir() && $transaksi->user_id !== Auth::id(),
            403,
            'Anda Tidak berhak melihat transaksi ini.'
        );
        

        $transaksi->load('details.barang', 'kasir');

        return view('kasir.show', compact('transaksi'));
    }
}
