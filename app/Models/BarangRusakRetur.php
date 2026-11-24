<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusakRetur extends Model
{
    // Kolom yang boleh diisi lewat create([...]).
    protected $fillable = [
        'barang_id', 'user_id', 'transaksi_id', 'tipe', 'jumlah',
        'tanggal', 'nama_pembeli', 'kembali_ke_stok', 'alasan', 'foto_bukti',
    ];

    // Ubah tipe data otomatis saat dibaca dari database.
    protected $casts = [
        'tanggal' => 'date',            // jadi objek tanggal, bisa di-format
        'kembali_ke_stok' => 'boolean', // 1/0 jadi true/false
    ];

    // Relasi: satu catatan milik satu barang.
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi: satu catatan dibuat oleh satu user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}