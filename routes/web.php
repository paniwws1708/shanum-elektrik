<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangRusakReturController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\KategoriController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanHarianController;
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

        // Modul Laporan Penjualan & Keuangan (Khusus Admin)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.pdf');

        // Log Aktivitas (Khusus Admin)
        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log-aktivitas.index');
    });

    // ================================================================
    // STAFF BARANG ROUTES
    // ================================================================
    Route::middleware('role:staff_barang')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])->name('dashboard');
    });

    // ================================================================
    // INVENTARIS (admin & staff barang) - modul Manajemen Barang dan Stok
    // ================================================================
    Route::middleware('role:admin,staff_barang')
        ->prefix('inventaris')
        ->as('inventaris.')
        ->group(function () {
            // Barang CRUD
            Route::resource('barang', BarangController::class);
            // Kategori CRUD (tanpa show)
            Route::resource('kategori', KategoriController::class)->except(['show']);

            // Monitoring Kondisi Stok (Admin & Staff Barang)
            Route::get('monitoring-stok', [LaporanController::class, 'monitoringStok'])->name('monitoring_stok.index');
        });

    // ================================================================
    // KASIR ROUTES
    // ================================================================
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kasirDashboard'])->name('dashboard');

        // ── Modul Transaksi Kasir (POS) — Stevani ──
        Route::get('/transaksi', [KasirController::class, 'pos'])->name('pos');
        Route::post('/transaksi', [KasirController::class, 'store'])->name('store');
    });

    // Riwayat & Detail Transaksi (Admin & Kasir)
    Route::middleware('role:admin,kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{transaksi}', [KasirController::class, 'show'])->name('show');
    });
});

// Fitur barang rusak & retur. 'auth' = harus login dulu.
// Pembatasan role (admin & staff_barang) ada di dalam controller.
Route::middleware('auth')
    ->prefix('barang-rusak-retur')   // URL: /barang-rusak-retur
    ->name('rusak-retur.')           // nama route: rusak-retur.index, dst
    ->group(function () {
        Route::get('/', [BarangRusakReturController::class, 'index'])->name('index');
        Route::get('/create', [BarangRusakReturController::class, 'create'])->name('create');
        Route::post('/', [BarangRusakReturController::class, 'store'])->name('store');
    });

// ===== Modul Keuangan (HANYA ADMIN) =====
// Isi array middleware:
//   'auth'     = harus login dulu
//   function   = pengecekan role. Selain admin langsung kena error 403.
// Pengecekan ditaruh di sini, jadi semua route di dalam grup otomatis terlindungi.
Route::middleware('auth', 'role:admin')
    ->prefix('keuangan')     // URL diawali /keuangan
    ->name('keuangan.')      // nama route diawali keuangan.
    ->group(function () {
        // Ringkasan
        Route::get('/', [KeuanganController::class, 'index'])->name('index');

        // Pemasukan
        Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
        Route::post('/pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');
        Route::delete('/pemasukan/{pemasukan}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

        // Pengeluaran
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

        // Penjualan harian
        Route::get('/penjualan-harian', [PenjualanHarianController::class, 'index'])->name('penjualan-harian.index');
        Route::post('/penjualan-harian', [PenjualanHarianController::class, 'catat'])->name('penjualan-harian.catat');
    });
