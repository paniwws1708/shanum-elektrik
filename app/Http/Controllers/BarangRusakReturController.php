<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRusakReturRequest;
use App\Models\Barang;            // CEK: pastikan model Barang ada di App\Models
use App\Models\BarangRusakRetur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BarangRusakReturController extends Controller implements HasMiddleware
{
    /**
     * Pembatasan akses: hanya admin dan staff_barang.
     * Kasir yang membuka URL ini langsung akan mendapat error 403 (dilarang).
     * Nilai role ('admin', 'staff_barang') sama persis dengan kolom role di tabel users.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, $next) {
                abort_unless(
                    in_array($request->user()?->role, ['admin', 'staff_barang']),
                    403,
                    'Anda tidak memiliki akses ke halaman ini.'
                );
                return $next($request);
            }),
        ];
    }

    /**
     * Halaman daftar catatan rusak/retur (dengan filter dan pagination).
     */
    public function index(Request $request)
    {
        // with(...) = ambil data barang & user sekaligus, supaya halaman cepat.
        $query = BarangRusakRetur::with(['barang', 'user'])
            ->latest('tanggal')
            ->latest('id');

        // Filter berdasarkan jenis (rusak / retur) jika dipilih.
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Pencarian berdasarkan nama atau kode barang.
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->whereHas('barang', function ($q) use ($cari) {
                $q->where('nama_barang', 'like', "%{$cari}%")
                  ->orWhere('kode_barang', 'like', "%{$cari}%");
            });
        }

        // paginate(10) = 10 data per halaman.
        // withQueryString() = filter tidak hilang saat pindah halaman.
        $data = $query->paginate(10)->withQueryString();

        return view('barang-rusak-retur.index', compact('data'));
    }

    /**
     * Menampilkan form tambah catatan.
     */
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')
            ->get(['id', 'kode_barang', 'nama_barang', 'stok']);

        return view('barang-rusak-retur.create', compact('barangs'));
    }

    /**
     * Menyimpan catatan baru + mengubah stok + mencatat riwayat stok.
     */
    public function store(StoreBarangRusakReturRequest $request)
    {
        // validated() = hanya data yang sudah lolos validasi.
        $data = $request->validated();

        // Simpan foto ke storage/app/public/bukti-rusak-retur
        $fotoPath = $request->file('foto_bukti')->store('bukti-rusak-retur', 'public');

        try {
            // Transaction = semua langkah di dalam harus berhasil semua.
            // Kalau satu gagal, semuanya dibatalkan (stok tidak jadi berubah).
            DB::transaction(function () use ($data, $fotoPath) {

                // lockForUpdate = kunci baris barang sebentar, supaya stok tidak
                // tertimpa kalau kasir sedang transaksi barang yang sama.
                $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

                $stokSebelum = $barang->stok;
                $perubahan = 0; // selisih stok (negatif = berkurang)

                if ($data['tipe'] === 'rusak') {
                    // Barang rusak tidak boleh lebih banyak dari stok yang ada.
                    if ($data['jumlah'] > $stokSebelum) {
                        throw ValidationException::withMessages([
                            'jumlah' => "Jumlah melebihi stok saat ini ({$stokSebelum}).",
                        ]);
                    }
                    $perubahan = -$data['jumlah'];   // stok berkurang
                } elseif (!empty($data['kembali_ke_stok'])) {
                    $perubahan = $data['jumlah'];    // retur & masih bagus: stok bertambah
                }

                // Hubungkan dengan transaksi penjualan (khusus retur) lewat no_invoice.
                // Kita hanya MEMBACA tabel transaksis, tidak mengubahnya.
                $transaksiId = null;
                if (!empty($data['no_invoice'])) {
                    $transaksiId = DB::table('transaksis')
                        ->where('no_invoice', $data['no_invoice'])
                        ->value('id');
                }

                // 1) Simpan catatan rusak/retur.
                $catatan = BarangRusakRetur::create([
                    'barang_id'       => $barang->id,
                    'user_id'         => auth()->id(),   // user yang sedang login
                    'transaksi_id'    => $transaksiId,
                    'tipe'            => $data['tipe'],
                    'jumlah'          => $data['jumlah'],
                    'tanggal'         => $data['tanggal'],
                    'nama_pembeli'    => $data['nama_pembeli'] ?? null,
                    // Untuk "rusak", kolom ini selalu false.
                    'kembali_ke_stok' => $data['tipe'] === 'retur' && !empty($data['kembali_ke_stok']),
                    'alasan'          => $data['alasan'],
                    'foto_bukti'      => $fotoPath,
                ]);

                // 2) Ubah stok barang & catat ke stok_riwayats (hanya jika stok benar-benar berubah).
                if ($perubahan !== 0) {
                    $stokSesudah = $stokSebelum + $perubahan;

                    $barang->stok = $stokSesudah;
                    $barang->save();

                    // Tabel stok_riwayats sudah ada (milik modul stok).
                    // Kita pakai tabel yang sama agar muncul di menu "Riwayat Stok".
                    DB::table('stok_riwayats')->insert([
                        'barang_id'        => $barang->id,
                        'user_id'          => auth()->id(),
                        'tipe'             => $data['tipe'],              // 'rusak' atau 'retur'
                        'jumlah_perubahan' => $perubahan,
                        'stok_sebelum'     => $stokSebelum,
                        'stok_sesudah'     => $stokSesudah,
                        'keterangan'       => ucfirst($data['tipe']) . ': ' . $data['alasan'],
                        'referensi_type'   => BarangRusakRetur::class,    // dari tabel mana asalnya
                        'referensi_id'     => $catatan->id,               // id catatan kita
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Kalau gagal, hapus foto yang sudah terlanjur tersimpan
            // supaya tidak ada file sampah, lalu lempar error lagi.
            Storage::disk('public')->delete($fotoPath);
            throw $e;
        }

        return redirect()
            ->route('rusak-retur.index')
            ->with('success', 'Catatan barang ' . $data['tipe'] . ' berhasil disimpan.');
    }
}