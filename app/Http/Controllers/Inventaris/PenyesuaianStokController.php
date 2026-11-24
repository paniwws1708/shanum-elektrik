<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventaris\StorePenyesuaianStokRequest;
use App\Models\Barang;
use App\Models\StokRiwayat;
use App\Services\StokService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenyesuaianStokController extends Controller
{
    public function index(Request $request): View
    {
        $query = StokRiwayat::with(['barang', 'user'])
            ->where('tipe', 'penyesuaian');

        $search = trim((string) $request->query('search'));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('barang', function ($barangQuery) use ($search) {
                        $barangQuery->where('nama_barang', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%");
                    });
            });
        }

        $penyesuaians = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'inventaris.penyesuaian_stok.index',
            compact('penyesuaians', 'search')
        );
    }

    public function create(): View
    {
        $barangs = Barang::orderBy('nama_barang')->get();

        return view(
            'inventaris.penyesuaian_stok.create',
            compact('barangs')
        );
    }

    public function store(
        StorePenyesuaianStokRequest $request,
        StokService $stokService
    ): RedirectResponse {
        $data = $request->validated();

        $stokService->setStokFisik(
            (int) $data['barang_id'],
            (int) $data['stok_fisik'],
            (int) $request->user()->id,
            $data['keterangan']
        );

        return redirect()
            ->route('inventaris.penyesuaian_stok.index')
            ->with(
                'success',
                'Penyesuaian stok fisik berhasil disimpan.'
            );
    }
}
