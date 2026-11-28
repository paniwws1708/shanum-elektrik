<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    // Pilihan kategori pengeluaran. Mau tambah kategori? Cukup tambah baris di sini.
    public const KATEGORI = [
        'pembelian_stok' => 'Pembelian Stok',
        'gaji' => 'Gaji Karyawan',
        'listrik_air' => 'Listrik & Air',
        'sewa' => 'Sewa Tempat',
        'operasional' => 'Operasional',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = ['user_id', 'tanggal', 'kategori', 'jumlah', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Filter periode, sama seperti di model Pemasukan.
    public function scopeAntara($query, $dari, $sampai)
    {
        return $query->whereBetween('tanggal', [$dari, $sampai]);
    }
}
