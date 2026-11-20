<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StokRiwayat;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service untuk mengelola semua perubahan stok barang.
 * Semua operasi menulis stok harus melalui service ini agar
 * tercatat dalam tabel `stok_riwayats` dan dilakukan secara atomik.
 */
class StokService
{
    /**
     * Tambah stok (mis. barang masuk).
     *
     * @param int   $barangId   ID barang yang stoknya ditambah
     * @param int   $jumlah     Jumlah penambahan (positif)
     * @param int   $userId     ID user yang melakukan aksi
     * @param string|null $keterangan  Catatan tambahan
     * @param mixed $referensi Optional model yang menjadi referensi (mis. BarangMasuk)
     * @return Barang Updated barang model
     * @throws Exception bila terjadi kegagalan transaksi
     */
    public function tambahStok(int $barangId, int $jumlah, int $userId, ?string $keterangan = null, $referensi = null): Barang
    {
        return DB::transaction(function () use ($barangId, $jumlah, $userId, $keterangan, $referensi) {
            /** @var Barang $barang */
            $barang = Barang::where('id', $barangId)->lockForUpdate()->firstOrFail();
            $stokSebelum = $barang->stok;
            $barang->increment('stok', $jumlah);
            $stokSesudah = $barang->stok;

            $riwayat = new StokRiwayat([
                'barang_id' => $barang->id,
                'user_id'   => $userId,
                'tipe'      => 'masuk',
                'jumlah_perubahan' => $jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
            ]);
            if ($referensi) {
                $riwayat->referensi()->associate($referensi);
            }
            $riwayat->save();

            return $barang;
        });
    }

    /**
     * Kurangi stok (mis. penjualan, barang rusak, retur).
     */
    public function kurangiStok(int $barangId, int $jumlah, int $userId, ?string $keterangan = null, $referensi = null): Barang
    {
        if ($jumlah <= 0) {
            throw new Exception('Jumlah pengurangan harus lebih besar dari 0');
        }
        return DB::transaction(function () use ($barangId, $jumlah, $userId, $keterangan, $referensi) {
            $barang = Barang::where('id', $barangId)->lockForUpdate()->firstOrFail();
            $stokSebelum = $barang->stok;
            if ($stokSebelum - $jumlah < 0) {
                throw new Exception('Stok tidak boleh menjadi negatif');
            }
            $barang->decrement('stok', $jumlah);
            $stokSesudah = $barang->stok;

            $riwayat = new StokRiwayat([
                'barang_id' => $barang->id,
                'user_id'   => $userId,
                'tipe'      => 'keluar',
                'jumlah_perubahan' => -$jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
            ]);
            if ($referensi) {
                $riwayat->referensi()->associate($referensi);
            }
            $riwayat->save();
            return $barang;
        });
    }

    /**
     * Penyesuaian stok (manual, bisa menambah atau mengurangi).
     */
    public function penyesuaianStok(int $barangId, int $perubahan, int $userId, ?string $keterangan = null, $referensi = null): Barang
    {
        return DB::transaction(function () use ($barangId, $perubahan, $userId, $keterangan, $referensi) {
            $barang = Barang::where('id', $barangId)->lockForUpdate()->firstOrFail();
            $stokSebelum = $barang->stok;
            $barang->increment('stok', $perubahan); // $perubahan dapat negatif
            $stokSesudah = $barang->stok;

            $tipe = $perubahan >= 0 ? 'penyesuaian' : 'penyesuaian';
            $riwayat = new StokRiwayat([
                'barang_id' => $barang->id,
                'user_id'   => $userId,
                'tipe'      => $tipe,
                'jumlah_perubahan' => $perubahan,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
            ]);
            if ($referensi) {
                $riwayat->referensi()->associate($referensi);
            }
            $riwayat->save();
            return $barang;
        });
    }
}
