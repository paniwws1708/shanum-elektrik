<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() = dijalankan saat "php artisan migrate" (membuat tabel)
    public function up(): void
    {
        Schema::create('barang_rusak_returs', function (Blueprint $table) {
            $table->id();

            // Barang yang rusak/diretur. Terhubung ke tabel "barangs" milik Destria.
            $table->foreignId('barang_id')->constrained('barangs');

            // Siapa yang mencatat. Terhubung ke tabel "users".
            $table->foreignId('user_id')->constrained('users');

            // Khusus retur: nomor transaksi asal (boleh kosong).
            // Terhubung ke tabel "transaksis" milik Stevani.
            // nullOnDelete = kalau transaksi dihapus, kolom ini jadi kosong (data kita tetap aman).
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();

            $table->string('tipe');                       // isinya: 'rusak' atau 'retur'
            $table->unsignedInteger('jumlah');            // jumlah barang
            $table->date('tanggal');                      // tanggal kejadian
            $table->string('nama_pembeli')->nullable();   // khusus retur (boleh kosong)

            // Khusus retur: true = barang masih bagus dan masuk lagi ke stok.
            $table->boolean('kembali_ke_stok')->default(false);

            $table->text('alasan');                       // penjelasan kenapa rusak/diretur
            $table->string('foto_bukti');                 // lokasi file foto bukti

            $table->timestamps();                         // created_at & updated_at otomatis
        });
    }

    // down() = dijalankan saat rollback (menghapus tabel)
    public function down(): void
    {
        Schema::dropIfExists('barang_rusak_returs');
    }
};