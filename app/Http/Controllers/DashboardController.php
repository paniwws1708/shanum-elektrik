<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the generic dashboard, redirecting based on role.
     */
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff_barang' => redirect()->route('staff.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            default => abort(403),
        };
    }

    /**
     * Admin dashboard.
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Staff Barang dashboard.
     */
    public function staffDashboard()
    {
        return view('dashboard.staff');
    }

    /**
     * Kasir dashboard.
     */
    public function kasirDashboard()
    {
        return view('dashboard.kasir');
    }
}
