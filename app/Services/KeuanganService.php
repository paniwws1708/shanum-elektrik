<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;

/**
 * Tempat semua perhitungan keuangan.
 * Satu perhitungan dipakai bersama: halaman Ringkasan milik kita
 * dan laporan keuangan milik Sri Bintang.
 *
 * Cara Sri Bintang memakainya:
 *   $data = \App\Services\KeuanganService::ringkasan('2026-11-01', '2026-11-30');
 *   $data['pemasukan'];  $data['pengeluaran'];  $data['saldo'];
 */
class KeuanganService
{
    public static function ringkasan(string $dari, string $sampai): array
    {
        // Total semua pemasukan dan pengeluaran pada periode itu.
        $totalPemasukan   = (float) Pemasukan::antara($dari, $sampai)->sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::antara($dari, $sampai)->sum('jumlah');

        return [
            'dari'        => $dari,
            'sampai'      => $sampai,
            'pemasukan'   => $totalPemasukan,
            'pengeluaran' => $totalPengeluaran,
            'saldo'       => $totalPemasukan - $totalPengeluaran, // selisih (arus kas)

            // Pemasukan dipecah per sumber.
            'pemasukan_penjualan' => (float) Pemasukan::antara($dari, $sampai)
                ->where('sumber', 'penjualan_harian')->sum('jumlah'),
            'pemasukan_lainnya' => (float) Pemasukan::antara($dari, $sampai)
                ->where('sumber', 'lainnya')->sum('jumlah'),

            // Pengeluaran dipecah per kategori, hasilnya seperti ['gaji' => 500000, ...]
            'pengeluaran_per_kategori' => Pengeluaran::antara($dari, $sampai)
                ->selectRaw('kategori, SUM(jumlah) as total')
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->pluck('total', 'kategori'),
        ];
    }
}