<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StokRiwayat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StokService
{
    /**
     * Menambahkan stok barang.
     */
    public function tambahStok(
        int $barangId,
        int $jumlah,
        int $userId,
        ?string $keterangan = null,
        ?Model $referensi = null
    ): Barang {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException(
                'Jumlah penambahan stok harus lebih besar dari 0.'
            );
        }

        return $this->terapkanPerubahan(
            $barangId,
            $jumlah,
            $userId,
            'masuk',
            $keterangan,
            $referensi
        );
    }

    /**
     * Mengurangi stok barang.
     */
    public function kurangiStok(
        int $barangId,
        int $jumlah,
        int $userId,
        ?string $keterangan = null,
        ?Model $referensi = null
    ): Barang {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException(
                'Jumlah pengurangan stok harus lebih besar dari 0.'
            );
        }

        return $this->terapkanPerubahan(
            $barangId,
            -$jumlah,
            $userId,
            'keluar',
            $keterangan,
            $referensi
        );
    }

    /**
     * Menyesuaikan stok secara manual.
     * Nilai positif berarti menambah, negatif berarti mengurangi.
     */
    public function penyesuaianStok(
        int $barangId,
        int $perubahan,
        int $userId,
        ?string $keterangan = null,
        ?Model $referensi = null
    ): Barang {
        if ($perubahan === 0) {
            throw new InvalidArgumentException(
                'Perubahan stok tidak boleh bernilai 0.'
            );
        }

        return $this->terapkanPerubahan(
            $barangId,
            $perubahan,
            $userId,
            'penyesuaian',
            $keterangan,
            $referensi
        );
    }

    /**
     * Menerapkan perubahan stok dan mencatat riwayat.
     */
    private function terapkanPerubahan(
        int $barangId,
        int $perubahan,
        int $userId,
        string $tipe,
        ?string $keterangan,
        ?Model $referensi
    ): Barang {
        return DB::transaction(function () use (
            $barangId,
            $perubahan,
            $userId,
            $tipe,
            $keterangan,
            $referensi
        ) {
            // Mengunci data barang selama transaksi berlangsung.
            $barang = Barang::whereKey($barangId)
                ->lockForUpdate()
                ->firstOrFail();

            $stokSebelum = $barang->stok;
            $stokSesudah = $stokSebelum + $perubahan;

            // Mencegah stok menjadi negatif.
            if ($stokSesudah < 0) {
                throw new InvalidArgumentException(
                    'Stok tidak mencukupi untuk perubahan tersebut.'
                );
            }

            // Memperbarui stok barang.
            $barang->stok = $stokSesudah;
            $barang->save();

            // Mencatat riwayat perubahan stok.
            $this->catatRiwayat(
                $barang,
                $userId,
                $tipe,
                $perubahan,
                $stokSebelum,
                $keterangan,
                $referensi
            );

            return $barang;
        });
    }

    /**
     * Menyimpan riwayat setiap perubahan stok.
     */
    private function catatRiwayat(
        Barang $barang,
        int $userId,
        string $tipe,
        int $jumlahPerubahan,
        int $stokSebelum,
        ?string $keterangan,
        ?Model $referensi
    ): void {
        $riwayat = new StokRiwayat([
            'barang_id' => $barang->id,
            'user_id' => $userId,
            'tipe' => $tipe,
            'jumlah_perubahan' => $jumlahPerubahan,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $barang->stok,
            'keterangan' => $keterangan,
        ]);

        // Menghubungkan riwayat dengan sumber transaksi.
        if ($referensi !== null) {
            $riwayat->referensi()->associate($referensi);
        }

        $riwayat->save();
    }
}
