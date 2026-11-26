<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() = dijalankan saat "php artisan migrate" (membuat tabel)
    public function up(): void
    {
        // ===== Tabel PEMASUKAN =====
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();

            // Admin yang mencatat. Terhubung ke tabel users.
            $table->foreignId('user_id')->constrained('users');

            $table->date('tanggal');          // tanggal pemasukan
            $table->string('sumber');         // 'penjualan_harian' atau 'lainnya'

            // Khusus pemasukan dari penjualan harian: tanggal penjualan yang direkap.
            // unique() = satu tanggal hanya boleh dicatat SATU kali (anti dobel).
            // Boleh kosong (null) untuk pemasukan 'lainnya', dan nilai kosong tidak bentrok.
            $table->date('tanggal_penjualan')->nullable()->unique();

            $table->decimal('jumlah', 15, 2);                      // nominal rupiah
            $table->unsignedInteger('jumlah_transaksi')->nullable(); // banyak transaksi (khusus penjualan harian)
            $table->string('keterangan');

            $table->timestamps();             // created_at & updated_at otomatis

            $table->index('tanggal');         // supaya filter tanggal cepat
        });

        // ===== Tabel PENGELUARAN =====
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal');
            $table->string('kategori');       // contoh: 'listrik', 'gaji', 'pembelian_stok'
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan');
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    // down() = dijalankan saat rollback (menghapus tabel)
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
        Schema::dropIfExists('pemasukans');
    }
};