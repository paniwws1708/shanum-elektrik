<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang dapat diisi secara mass assignment.
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'harga_modal',
        'harga_jual',
        'stok',
        'stok_minimum',
        'lokasi_penyimpanan',
        'foto',
    ];

    /**
     * Konversi tipe data atribut.
     */
    protected $casts = [
        'harga_modal'  => 'decimal:2',
        'harga_jual'   => 'decimal:2',
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke kategori barang.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke seluruh transaksi barang masuk.
     */
    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Relasi ke seluruh riwayat perubahan stok.
     */
    public function stokRiwayats(): HasMany
    {
        return $this->hasMany(StokRiwayat::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Pencarian berdasarkan nama atau kode barang.
     */
    public function scopeCari(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('nama_barang', 'like', "%{$term}%")
                ->orWhere('kode_barang', 'like', "%{$term}%");
        });
    }

    /**
     * Mengambil barang dengan stok menipis.
     */
    public function scopeStokMenipis(Builder $query): Builder
    {
        return $query
            ->where('stok', '>', 0)
            ->whereColumn('stok', '<=', 'stok_minimum');
    }

    /**
     * Mengambil barang yang stoknya habis.
     */
    public function scopeStokHabis(Builder $query): Builder
    {
        return $query->where('stok', '<=', 0);
    }

    /**
     * Mengambil barang yang masih memiliki stok.
     */
    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Status stok: habis, menipis, atau aman.
     */
    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'habis';
        }

        if ($this->stok <= $this->stok_minimum) {
            return 'menipis';
        }

        return 'aman';
    }
}
