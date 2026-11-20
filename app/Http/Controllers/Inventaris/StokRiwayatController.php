<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\StokRiwayat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StokRiwayatController extends Controller
{
    /**
     * Tampilkan riwayat perubahan stok.
     */
    public function index(Request $request): View
    {
        $query = StokRiwayat::with(['barang', 'user']);
        if ($search = $request->query('search')) {
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%$search%");
            });
        }
        $riwayats = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('inventaris.stok_riwayat.index', compact('riwayats'));
    }
}
