<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanHarianController extends Controller
{
    /**
     * Rekap penjualan per hari (30 hari terakhir), dihitung dari tabel transaksis.
     * Kita hanya MEMBACA tabel transaksis (milik Stevani), tidak mengubahnya.
     */
    public function index()
    {
        $mulai = now()->subDays(29)->toDateString();

        // Kelompokkan transaksi per tanggal: hitung banyaknya dan jumlahkan totalnya.
        $rekap = DB::table('transaksis')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total_harga) as total')
            ->whereDate('created_at', '>=', $mulai)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('tanggal')
            ->get();

        // Tanggal mana saja yang SUDAH dicatat sebagai pemasukan.
        // Hasilnya seperti ['2026-11-25' => 7, ...]. Pakai DB::table agar tanggalnya
        // berupa teks biasa (bukan objek Carbon) dan bisa dicocokkan di view.
        $sudahDicatat = DB::table('pemasukans')
            ->whereNotNull('tanggal_penjualan')
            ->pluck('id', 'tanggal_penjualan');

        return view('keuangan.penjualan-harian', compact('rekap', 'sudahDicatat'));
    }

    /**
     * Catat total penjualan satu hari menjadi pemasukan.
     */
    public function catat(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
        ]);
        $tanggal = $request->tanggal;

        // Cegah dobel: tanggal ini sudah pernah dicatat?
        if (Pemasukan::whereDate('tanggal_penjualan', $tanggal)->exists()) {
            return back()->withErrors(['tanggal' => 'Penjualan tanggal itu sudah pernah dicatat.']);
        }

        // Hitung ulang dari database. Angka TIDAK diambil dari form,
        // jadi tidak bisa dipalsukan dan pasti cocok dengan data kasir.
        $rekap = DB::table('transaksis')
            ->whereDate('created_at', $tanggal)
            ->selectRaw('COUNT(*) as jumlah_transaksi, COALESCE(SUM(total_harga), 0) as total')
            ->first();

        if ($rekap->jumlah_transaksi == 0) {
            return back()->withErrors(['tanggal' => 'Tidak ada transaksi pada tanggal itu.']);
        }

        Pemasukan::create([
            'user_id'           => auth()->id(),
            'tanggal'           => $tanggal,
            'sumber'            => 'penjualan_harian',
            'tanggal_penjualan' => $tanggal, // kunci penghubung ke data transaksi
            'jumlah'            => $rekap->total,
            'jumlah_transaksi'  => $rekap->jumlah_transaksi,
            'keterangan'        => 'Penjualan harian ' . $tanggal . ' (' . $rekap->jumlah_transaksi . ' transaksi)',
        ]);

        return redirect()->route('keuangan.penjualan-harian.index')
            ->with('success', 'Penjualan tanggal ' . $tanggal . ' berhasil dicatat sebagai pemasukan.');
    }
}