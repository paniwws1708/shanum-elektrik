<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengeluaranRequest;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Daftar pengeluaran + form tambah (satu halaman).
     */
    public function index(Request $request)
    {
        $base = Pengeluaran::query();

        if ($request->filled('kategori')) {
            $base->where('kategori', $request->kategori);
        }
        if ($request->filled('dari')) {
            $base->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $base->whereDate('tanggal', '<=', $request->sampai);
        }

        $total = (clone $base)->sum('jumlah');

        $data = $base->with('user')
            ->latest('tanggal')->latest('id')
            ->paginate(10)->withQueryString();

        return view('keuangan.pengeluaran', compact('data', 'total'));
    }

    public function store(StorePengeluaranRequest $request)
    {
        $data = $request->validated();

        Pengeluaran::create([
            'user_id' => auth()->id(),
            'tanggal' => $data['tanggal'],
            'kategori' => $data['kategori'],
            'jumlah' => $data['jumlah'],
            'keterangan' => $data['keterangan'],
        ]);

        return redirect()->route('keuangan.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('keuangan.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
