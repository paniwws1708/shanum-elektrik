<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    // Pilihan sumber pemasukan (kunci = isi database, nilai = tulisan di layar).
    public const SUMBER = [
        'penjualan_harian' => 'Penjualan Harian',
        'lainnya' => 'Lainnya',
    ];

    // Kolom yang boleh diisi lewat create([...]).
    protected $fillable = [
        'user_id', 'tanggal', 'sumber', 'tanggal_penjualan',
        'jumlah', 'jumlah_transaksi', 'keterangan',
    ];

    // Ubah tipe data otomatis saat dibaca dari database.
    protected $casts = [
        'tanggal' => 'date',
        'tanggal_penjualan' => 'date',
        'jumlah' => 'decimal:2',
    ];

    // Relasi: pemasukan dicatat oleh satu user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope = "filter siap pakai". Contoh pemakaian:
    // Pemasukan::antara('2026-11-01', '2026-11-30')->sum('jumlah');
    public function scopeAntara($query, $dari, $sampai)
    {
        return $query->whereBetween('tanggal', [$dari, $sampai]);
    }
}
