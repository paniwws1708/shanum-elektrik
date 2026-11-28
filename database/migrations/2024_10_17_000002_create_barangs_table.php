<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->foreignId('kategori_id')->constrained('kategoris')->restrictOnDelete();
            $table->decimal('harga_modal', 15, 2)->unsigned();
            $table->decimal('harga_jual', 15, 2)->unsigned();
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(10);
            $table->string('lokasi_penyimpanan')->nullable();
            $table->string('foto')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        // Indexes for search
        Schema::table('barangs', function (Blueprint $table) {
            $table->index('kode_barang');
            $table->index('nama_barang');
            $table->index('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
