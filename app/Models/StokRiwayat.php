<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StokRiwayat extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara mass assignment.
     */
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

    /**
     * Relasi ke barang, termasuk barang yang sudah di-soft delete.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class)->withTrashed();
    }

    /**
     * Relasi ke pengguna yang melakukan perubahan stok.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi polymorphic ke sumber transaksi stok.
     */
    public function referensi(): MorphTo
    {
        return $this->morphTo();
    }
}
