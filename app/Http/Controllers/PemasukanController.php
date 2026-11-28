<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemasukanRequest;
use App\Models\Pemasukan;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    /**
     * Daftar pemasukan + form tambah (satu halaman).
     */
    public function index(Request $request)
    {
        // $base = query dasar berisi filter saja (tanpa urutan),
        // dipakai untuk menghitung total dan untuk daftar.
        $base = Pemasukan::query();

        if ($request->filled('sumber')) {
            $base->where('sumber', $request->sumber);
        }
        if ($request->filled('dari')) {
            $base->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $base->whereDate('tanggal', '<=', $request->sampai);
        }

        // Total dari data yang sedang difilter (tampil di atas tabel).
        $total = (clone $base)->sum('jumlah');

        // Daftar: terbaru di atas, 10 data per halaman.
        // withQueryString() = filter tidak hilang saat pindah halaman.
        $data = $base->with('user')
            ->latest('tanggal')->latest('id')
            ->paginate(10)->withQueryString();

        return view('keuangan.pemasukan', compact('data', 'total'));
    }

    /**
     * Simpan pemasukan baru. Lewat form ini sumbernya selalu 'lainnya'.
     * Pemasukan dari penjualan hanya dibuat lewat halaman Penjualan Harian.
     */
    public function store(StorePemasukanRequest $request)
    {
        $data = $request->validated(); // hanya data yang lolos validasi

        Pemasukan::create([
            'user_id' => auth()->id(), // admin yang sedang login
            'tanggal' => $data['tanggal'],
            'sumber' => 'lainnya',
            'jumlah' => $data['jumlah'],
            'keterangan' => $data['keterangan'],
        ]);

        return redirect()->route('keuangan.pemasukan.index')
            ->with('success', 'Pemasukan berhasil dicatat.');
    }

    /**
     * Hapus pemasukan (untuk membetulkan salah input).
     * Kalau yang dihapus adalah pemasukan penjualan harian,
     * tanggal itu bisa dicatat ulang dari halaman Penjualan Harian.
     */
    public function destroy(Pemasukan $pemasukan)
    {
        $pemasukan->delete();

        return redirect()->route('keuangan.pemasukan.index')
            ->with('success', 'Pemasukan berhasil dihapus.');
    }
}
