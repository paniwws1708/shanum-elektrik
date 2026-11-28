<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Auto-redirect ke dashboard sesuai role.
     */
    public function index()
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff_barang' => redirect()->route('staff.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            default => abort(403),
        };
    }

    /**
     * Dashboard Admin.
     */
    public function adminDashboard()
    {
        $hariIni = now()->toDateString();
        $bulanMulai = now()->startOfMonth();
        $bulanSelesai = now()->endOfMonth();

        $hasTransaksi = Schema::hasTable('transaksis');
        $hasDetail = Schema::hasTable('detail_transaksis');
        $hasBarang = Schema::hasTable('barangs');
        $hasPengeluaran = Schema::hasTable('pengeluarans');

        $omzetBulanIni = 0.0;
        $jumlahTransaksiBulanIni = 0;
        $transaksiHariIni = 0;
        $pendapatanHariIni = 0.0;

        if ($hasTransaksi) {
            $transaksiBulanIni = Transaksi::whereBetween('created_at', [$bulanMulai, $bulanSelesai]);
            $omzetBulanIni = (float) (clone $transaksiBulanIni)->sum('total_harga');
            $jumlahTransaksiBulanIni = (clone $transaksiBulanIni)->count();
            $transaksiHariIni = Transaksi::whereDate('created_at', $hariIni)->count();
            $pendapatanHariIni = (float) Transaksi::whereDate('created_at', $hariIni)->sum('total_harga');
        }

        $labaKotorBulanIni = 0.0;
        if ($hasTransaksi && $hasDetail && $hasBarang) {
            $labaKotorBulanIni = (float) DetailTransaksi::query()
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->leftJoin('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
                ->whereBetween('transaksis.created_at', [$bulanMulai, $bulanSelesai])
                ->selectRaw('COALESCE(SUM((detail_transaksis.harga_satuan - COALESCE(barangs.harga_modal, 0)) * detail_transaksis.jumlah), 0) as laba')
                ->value('laba');
        }

        $pengeluaranBulanIni = $hasPengeluaran
            ? (float) Pengeluaran::whereBetween('tanggal', [
                $bulanMulai->toDateString(),
                $bulanSelesai->toDateString(),
            ])->sum('jumlah')
            : 0.0;

        $stokHabis = $hasBarang ? Barang::stokHabis()->count() : 0;
        $stokMenipisOnly = $hasBarang ? Barang::stokMenipis()->count() : 0;

        $stats = [
            'omzet_bulan_ini' => $omzetBulanIni,
            'transaksi_bulan_ini' => $jumlahTransaksiBulanIni,
            'keuntungan_bersih' => $labaKotorBulanIni - $pengeluaranBulanIni,
            'transaksi_hari_ini' => $transaksiHariIni,
            'pendapatan_hari_ini' => $pendapatanHariIni,
            'stok_menipis' => $stokMenipisOnly + $stokHabis,
            'stok_habis' => $stokHabis,
        ];

        $penjualan7Hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $total = $hasTransaksi
                ? (float) Transaksi::whereDate('created_at', $tanggal->toDateString())->sum('total_harga')
                : 0.0;
            $penjualan7Hari->push([
                'label' => $i === 0 ? 'Hari ini' : $tanggal->translatedFormat('D'),
                'total' => $total,
                'juta' => $total / 1000000,
                'is_today' => $i === 0,
            ]);
        }

        $palingSeringTerjual = $hasDetail
            ? DetailTransaksi::query()
                ->select('nama_barang', DB::raw('SUM(jumlah) as total_terjual'))
                ->groupBy('nama_barang')
                ->orderByDesc('total_terjual')
                ->take(4)
                ->get()
            : collect();

        $palingJarangTerjual = $hasDetail
            ? DetailTransaksi::query()
                ->select('nama_barang', DB::raw('SUM(jumlah) as total_terjual'))
                ->groupBy('nama_barang')
                ->orderBy('total_terjual')
                ->take(4)
                ->get()
            : collect();

        $restockMenunggu = collect();

        return view('dashboard.admin', compact(
            'stats',
            'penjualan7Hari',
            'palingSeringTerjual',
            'palingJarangTerjual',
            'restockMenunggu'
        ));
    }

    /**
     * Dashboard Staff Barang.
     */
    public function staffDashboard()
    {
        $hasBarang = Schema::hasTable('barangs');
        $hasKategori = Schema::hasTable('kategoris');

        $stokHabis = $hasBarang ? Barang::stokHabis()->count() : 0;
        $stokMenipisOnly = $hasBarang ? Barang::stokMenipis()->count() : 0;

        $stats = [
            'total_produk' => $hasBarang ? Barang::count() : 0,
            'total_kategori' => $hasKategori ? Kategori::count() : 0,
            'stok_menipis' => $stokMenipisOnly + $stokHabis,
            'stok_habis' => $stokHabis,
            'restock_aktif' => 0,
        ];

        $barangPerhatian = $hasBarang
            ? Barang::with('kategori')
                ->where('stok', '<=', 20)
                ->orderBy('stok')
                ->take(6)
                ->get()
            : collect();

        $pengajuanSaya = collect();

        return view('dashboard.staff', compact('stats', 'barangPerhatian', 'pengajuanSaya'));
    }

    /**
     * Dashboard Kasir — tampilkan statistik hari ini & 7 hari terakhir dari data nyata.
     */
    public function kasirDashboard()
    {
        $userId = Auth::id();
        $hariIni = now()->toDateString();
        $tujuhHariLalu = now()->subDays(6)->startOfDay();
        $hasTransaksi = Schema::hasTable('transaksis');

        $jumlahHariIni = 0;
        $penjualanHariIni = 0.0;
        $penjualan7Hari = 0.0;
        $jumlah7Hari = 0;

        if ($hasTransaksi) {
            $transaksiHariIni = Transaksi::where('user_id', $userId)
                ->whereDate('created_at', $hariIni);

            $jumlahHariIni = (clone $transaksiHariIni)->count();
            $penjualanHariIni = (float) (clone $transaksiHariIni)->sum('total_harga');

            $transaksi7Hari = Transaksi::where('user_id', $userId)
                ->where('created_at', '>=', $tujuhHariLalu);

            $penjualan7Hari = (float) (clone $transaksi7Hari)->sum('total_harga');
            $jumlah7Hari = (clone $transaksi7Hari)->count();
        }

        $stats = [
            'pendapatan_hari_ini' => $penjualanHariIni,
            'transaksi_hari_ini' => $jumlahHariIni,
            'rata_rata' => $jumlahHariIni > 0 ? $penjualanHariIni / $jumlahHariIni : 0,
            'penjualan_7_hari' => $penjualan7Hari,
            'transaksi_7_hari' => $jumlah7Hari,
        ];

        $laporan7Hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $jumlahRow = 0;
            $totalRow = 0.0;

            if ($hasTransaksi) {
                $queryHari = Transaksi::where('user_id', $userId)
                    ->whereDate('created_at', $tanggal->toDateString());
                $jumlahRow = (clone $queryHari)->count();
                $totalRow = (float) (clone $queryHari)->sum('total_harga');
            }

            $laporan7Hari->push([
                'hari' => $i === 0 ? 'Hari ini' : $tanggal->translatedFormat('D, d M'),
                'jumlah_transaksi' => $jumlahRow,
                'total' => $totalRow,
                'is_today' => $i === 0,
            ]);
        }

        $transaksiTerakhir = $hasTransaksi
            ? Transaksi::where('user_id', $userId)->latest()->take(5)->get()
            : collect();

        return view('dashboard.kasir', compact('stats', 'laporan7Hari', 'transaksiTerakhir'));
    }
}
