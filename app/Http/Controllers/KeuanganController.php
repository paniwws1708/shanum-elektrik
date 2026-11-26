<?php

namespace App\Http\Controllers;

use App\Services\KeuanganService;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Validasi filter periode (boleh kosong).
        $request->validate([
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        // Kalau tidak diisi: awal bulan ini sampai hari ini.
        $dari   = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        // Perhitungan diserahkan ke service supaya bisa dipakai laporan juga.
        $ringkasan = KeuanganService::ringkasan($dari, $sampai);

        return view('keuangan.index', compact('ringkasan', 'dari', 'sampai'));
    }
}