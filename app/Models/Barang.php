<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
        'harga_modal'  => 'decimal:2',
        'harga_jual'   => 'decimal:2',
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
    ];

    /** Relationships */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function stokRiwayats(): HasMany
    {
        return $this->hasMany(StokRiwayat::class);
    }

    /** Scopes */
    public function scopeCari($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nama_barang', 'like', "%{$term}%")
              ->orWhere('kode_barang', 'like', "%{$term}%");
        });
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '>', 0)
                     ->whereColumn('stok', '<=', 'stok_minimum');
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stok', 0);
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /** Status stok: habis | menipis | aman */
    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'habis';
        }
        return $this->stok <= $this->stok_minimum ? 'menipis' : 'aman';
    }
}
