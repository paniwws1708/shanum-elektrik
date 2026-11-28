<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Services\KeuanganService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Validasi filter periode (boleh kosong).
        $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        // Kalau tidak diisi: awal bulan ini sampai hari ini.
        $dari = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        // Perhitungan diserahkan ke service supaya bisa dipakai laporan juga.
        $ringkasan = KeuanganService::ringkasan($dari, $sampai);

        $hariIni = now()->toDateString();
        $hasTransaksi = Schema::hasTable('transaksis');
        $hasDetail = Schema::hasTable('detail_transaksis');
        $hasBarang = Schema::hasTable('barangs');

        $penjualanHariIni = $hasTransaksi
            ? (float) Transaksi::whereDate('created_at', $hariIni)->sum('total_harga')
            : 0.0;
        $transaksiHariIniCount = $hasTransaksi
            ? Transaksi::whereDate('created_at', $hariIni)->count()
            : 0;

        $labaKotor = 0.0;
        if ($hasTransaksi && $hasDetail && $hasBarang) {
            $labaKotor = (float) DetailTransaksi::query()
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->leftJoin('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
                ->whereDate('transaksis.created_at', '>=', $dari)
                ->whereDate('transaksis.created_at', '<=', $sampai)
                ->selectRaw('COALESCE(SUM((detail_transaksis.harga_satuan - COALESCE(barangs.harga_modal, 0)) * detail_transaksis.jumlah), 0) as laba')
                ->value('laba');
        }

        $keuntunganBersih = $labaKotor > 0
            ? ($labaKotor - (float) $ringkasan['pengeluaran'])
            : (float) $ringkasan['saldo'];

        $pemasukanTerbaru = Schema::hasTable('pemasukans')
            ? Pemasukan::latest('tanggal')->latest('id')->take(5)->get()
            : collect();

        $pengeluaranTerbaru = Schema::hasTable('pengeluarans')
            ? Pengeluaran::latest('tanggal')->latest('id')->take(5)->get()
            : collect();

        return view('keuangan.index', compact(
            'ringkasan',
            'dari',
            'sampai',
            'penjualanHariIni',
            'transaksiHariIniCount',
            'keuntunganBersih',
            'pemasukanTerbaru',
            'pengeluaranTerbaru'
        ));
    }
}
