<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Transaksi extends Model
{
    public const AWALAN_INVOICE ='INV-';
    public const PANJANG_URUTAN = 4;


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



    /** relasi details() yang menyatakan satu transaksi punya banyak detail (1:N) */
    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    /** relasi kasir() yang menyatakan satu transaksi hanya dimiliki oleh 1 user (1:N) */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }







    public static function buatNoInvoice(): string
    {
        // 1. awalan nomor hari ini : INV-tanggal-
        $tanggal = now()->format('Ymd');
        $prefix = self::AWALAN_INVOICE . $tanggal . '-';

        // 2. cari nomor invoice terakhir hari ini 
        $terakhir = static::where ('no_invoice', 'like', $prefix . '%')
                            ->orderBy ('no_invoice')
                            ->value('no_invoice');


        // 3. kalau ada: ambil 4 digit terakhir lalu tambah 1; kalau tidak mulai dari 1
        if ($terakhir) {
            $urutan = (int) substr($terakhir, -self::PANJANG_URUTAN) + 1;
        } else {
            $urutan = 1;
        }

        // 4. gabungkan awalan + urutan 4 digit (diisi nol di depan)
        return $prefix . str_pad($urutan, self::PANJANG_URUTAN, '0', STR_PAD_LEFT);


    }

}