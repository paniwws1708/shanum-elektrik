<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Auto-redirect ke dashboard sesuai role.
     */
    public function index()
    {
        return match (Auth::user()->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'staff_barang'=> redirect()->route('staff.dashboard'),
            'kasir'       => redirect()->route('kasir.dashboard'),
            default       => abort(403),
        };
    }

    /**
     * Dashboard Admin.
     */
    public function adminDashboard()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_admin'        => User::where('role', 'admin')->count(),
            'total_staff'        => User::where('role', 'staff_barang')->count(),
            'total_kasir'        => User::where('role', 'kasir')->count(),
            // Nanti diisi dari model produk/transaksi
            'total_produk'       => 0,
            'transaksi_hari_ini' => 0,
            'pendapatan_hari_ini'=> 0,
        ];

        $recentUsers = User::latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recentUsers'));
    }

    /**
     * Dashboard Staff Barang.
     */
    public function staffDashboard()
    {
        $stats = [
            'total_produk'   => \App\Models\Barang::count(),
            'stok_menipis'   => \App\Models\Barang::stokMenipis()->count(),
            'stok_habis'     => \App\Models\Barang::stokHabis()->count(),
        ];

        return view('dashboard.staff', compact('stats'));
    }

    /**
     * Dashboard Kasir — tampilkan statistik hari ini dari data nyata.
     */
    public function kasirDashboard()
    {
        $userId = Auth::id();
        $hariIni = now()->toDateString();

        // Transaksi milik kasir yang login, dibuat hari ini
        $transaksiHariIni = Transaksi::where('user_id', $userId)
            ->whereDate('created_at', $hariIni);

        $stats = [
            'transaksi_hari_ini'  => (clone $transaksiHariIni)->count(),
            'pendapatan_hari_ini' => (clone $transaksiHariIni)->sum('total_harga'),
            'item_terjual'        => DetailTransaksi::whereIn(
                                        'transaksi_id',
                                        (clone $transaksiHariIni)->pluck('id')
                                     )->sum('jumlah'),
        ];

        return view('dashboard.kasir', compact('stats'));
    }
}
