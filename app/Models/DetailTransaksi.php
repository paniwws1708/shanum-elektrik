<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model DetailTransaksi — satu baris item dalam struk transaksi.
 */
class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'nama_barang',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // ───────────────────────────── Relasi ─────────────────────────────

    /** Transaksi induk */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    /** Barang yang dijual */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
