<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder Transaksi — buat 30 transaksi contoh.
 * Memakai barang yang SUDAH ADA di tabel barangs dan user kasir dari UserSeeder.
 * Seeder ini TIDAK mengurangi stok barang (sesuai instruksi).
 */
class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user kasir
        $kasir = User::where('role', 'kasir')->first();
        if (! $kasir) {
            $this->command->warn('User kasir tidak ditemukan. TransaksiSeeder dilewati.');

            return;
        }

        // Ambil barang yang tersedia
        $barangs = Barang::where('stok', '>', 0)->get();
        if ($barangs->isEmpty()) {
            $this->command->warn('Tabel barangs kosong atau tidak ada stok. TransaksiSeeder dilewati.');

            return;
        }

        $this->command->info('Membuat 30 transaksi contoh...');

        for ($i = 1; $i <= 30; $i++) {
            // Ambil 1-4 barang acak untuk tiap transaksi
            $itemCount = rand(1, min(4, $barangs->count()));
            $barangDipilih = $barangs->random($itemCount);

            $totalHarga = 0;
            $detailRows = [];

            foreach ($barangDipilih as $barang) {
                $qty = rand(1, 3);
                $subtotal = $barang->harga_jual * $qty;
                $totalHarga += $subtotal;

                $detailRows[] = [
                    'barang_id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'harga_satuan' => $barang->harga_jual,
                    'jumlah' => $qty,
                    'subtotal' => $subtotal,
                ];
            }

            // Bayar dibulatkan ke atas (kelipatan 1000) agar realistis
            $bayar = ceil($totalHarga / 1000) * 1000;
            $kembalian = $bayar - $totalHarga;

            // Buat transaksi dengan tanggal acak 30 hari terakhir
            $tanggal = now()->subDays(rand(0, 29))->addHours(rand(8, 20));

            $transaksi = Transaksi::create([
                'no_invoice' => 'INV-'.$tanggal->format('Ymd').'-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $kasir->id,
                'total_harga' => $totalHarga,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Simpan detail transaksi
            foreach ($detailRows as $row) {
                $transaksi->details()->create($row);
            }
        }

        $this->command->info('30 transaksi contoh berhasil dibuat.');
    }
}
