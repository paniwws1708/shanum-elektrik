<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Transaksi — mewakili satu transaksi penjualan di kasir.
 */
class Transaksi extends Model
{
    protected $fillable = [
        'no_invoice',
        'user_id',
        'total_harga',
        'bayar',
        'kembalian',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'bayar'       => 'decimal:2',
        'kembalian'   => 'decimal:2',
    ];

    // ───────────────────────────── Relasi ─────────────────────────────

    /** Detail item dalam transaksi ini */
    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    /** Kasir yang melayani transaksi */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ───────────────────────── Generate Invoice ──────────────────────

    /**
     * Buat nomor invoice unik berformat INV-YYYYMMDD-XXXX.
     * Contoh: INV-20261121-0001
     */
    public static function buatNoInvoice(): string
    {
        $tanggal = now()->format('Ymd');
        $prefix  = 'INV-' . $tanggal . '-';

        // Cari invoice terakhir hari ini
        $terakhir = static::where('no_invoice', 'like', $prefix . '%')
            ->orderByDesc('no_invoice')
            ->value('no_invoice');

        if ($terakhir) {
            // Ambil 4 digit terakhir, tambah 1
            $urutan = (int) substr($terakhir, -4) + 1;
        } else {
            $urutan = 1;
        }

        return $prefix . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
