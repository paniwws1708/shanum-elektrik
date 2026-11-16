<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================================================================
// GUEST ROUTES (belum login)
// ================================================================
Route::middleware('guest')->group(function () {

    // Redirect root ke login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Forgot Password - via Email
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    // Reset Password - via Email Link
    Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

    // Direct Reset Password - Offline mode
    Route::get('/direct-reset-password', [ForgotPasswordController::class, 'showDirectResetForm'])->name('password.direct');
    Route::post('/direct-reset-password', [ForgotPasswordController::class, 'directResetPassword'])->name('password.direct.update');
});

// ================================================================
// AUTHENTICATED ROUTES (sudah login)
// ================================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Generic dashboard (auto-redirect berdasarkan role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================================================================
    // PROFIL (semua role bisa akses)
    // ================================================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // ================================================================
    // ADMIN ROUTES
    // ================================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        // Route Manajemen User, Laporan, dsb — tambahkan di tahap berikutnya
    });

    // ================================================================
    // STAFF BARANG ROUTES
    // ================================================================
    Route::middleware('role:staff_barang')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])->name('dashboard');
        // Route Data Barang, Stok, dsb — tambahkan di tahap berikutnya
    });

    // ================================================================
    // KASIR ROUTES
    // ================================================================
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kasirDashboard'])->name('dashboard');
        // Route Transaksi, Riwayat, dsb — tambahkan di tahap berikutnya
    });
});
