<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\StokRiwayat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StokRiwayatController extends Controller
{
    /**
     * Menampilkan seluruh riwayat perubahan stok barang.
     */
    public function index(Request $request): View
    {
        // Mengambil riwayat beserta relasi barang dan pengguna.
        $query = StokRiwayat::with(['barang', 'user']);

        // Pencarian nama dan kode barang.
        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->whereHas('barang', function ($barangQuery) use ($search) {
                $barangQuery->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            });
        }

        // Filter berdasarkan tipe perubahan stok.
        $tipe = $request->query('tipe', '');

        $tipeValid = ['masuk', 'keluar', 'penyesuaian'];

        if (in_array($tipe, $tipeValid, true)) {
            $query->where('tipe', $tipe);
        } else {
            $tipe = '';
        }

        // Mengurutkan riwayat dari yang terbaru.
        $riwayats = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('inventaris.stok_riwayat.index', [
            'riwayats' => $riwayats,
            'search' => $search,
            'tipe' => $tipe,
        ]);
    }
}
