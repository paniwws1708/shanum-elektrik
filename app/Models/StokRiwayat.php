<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokRiwayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'user_id',
        'tipe',
        'jumlah_perubahan',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'referensi_type',
        'referensi_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referensi()
    {
        return $this->morphTo();
    }
}
