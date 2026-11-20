<?php

namespace App\Http\Controllers;

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
            'total_produk'  => 0,
            'stok_menipis'  => 0,
            'stok_habis'    => 0,
        ];

        return view('dashboard.staff', compact('stats'));
    }

    /**
     * Dashboard Kasir.
     */
    public function kasirDashboard()
    {
        $stats = [
            'transaksi_hari_ini'  => 0,
            'pendapatan_hari_ini' => 0,
            'item_terjual'        => 0,
        ];

        return view('dashboard.kasir', compact('stats'));
    }
}
