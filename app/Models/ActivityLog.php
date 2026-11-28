<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'modul',
        'aksi',
        'deskripsi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper sederhana untuk mencatat aktivitas pengguna.
     */
    public static function catat(string $modul, string $aksi, string $deskripsi, ?int $userId = null): void
    {
        try {
            if (! Schema::hasTable('activity_logs')) {
                return;
            }

            static::create([
                'user_id' => $userId ?? Auth::id(),
                'modul' => $modul,
                'aksi' => $aksi,
                'deskripsi' => $deskripsi,
            ]);
        } catch (\Throwable $e) {
            // Abaikan error logging agar tidak mengganggu proses utama
        }
    }
}
