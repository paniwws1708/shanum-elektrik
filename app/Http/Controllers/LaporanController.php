<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Services\KeuanganService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function getDataLaporan(Request $request): array
    {
        $periode = $request->input('periode', 'harian');
        $today = Carbon::today();

        if ($request->filled('dari') && $request->filled('sampai')) {
            $startDate = $request->input('dari');
            $endDate = $request->input('sampai');
        } else {
            switch ($periode) {
                case 'mingguan':
                    $startDate = $today->copy()->startOfWeek()->toDateString();
                    $endDate = $today->copy()->endOfWeek()->toDateString();
                    break;
                case 'bulanan':
                    $startDate = $today->copy()->startOfMonth()->toDateString();
                    $endDate = $today->copy()->endOfMonth()->toDateString();
                    break;
                case 'harian':
                default:
                    $startDate = $today->toDateString();
                    $endDate = $today->toDateString();
                    break;
            }
        }

        // Memanggil jembatan perhitungan keuangan dari KeuanganService
        $ringkasan = KeuanganService::ringkasan($startDate, $endDate);

        // Mengambil daftar rincian pemasukan & pengeluaran pada periode tersebut
        // Mengambil daftar rincian pemasukan & pengeluaran pada periode tersebut
        $daftarPemasukan = Pemasukan::antara($startDate, $endDate)->latest('tanggal')->get();
        $daftarPengeluaran = Pengeluaran::antara($startDate, $endDate)->latest('tanggal')->get();

        return [
            'periode' => $periode,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'ringkasan' => $ringkasan,
            'daftarPemasukan' => $daftarPemasukan,
            'daftarPengeluaran' => $daftarPengeluaran,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getDataLaporan($request);
        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getDataLaporan($request);
        $pdf = Pdf::loadView('laporan.pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download("Laporan-Keuangan-{$data['startDate']}-sd-{$data['endDate']}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getDataLaporan($request);
        $filename = "Laporan-Keuangan-{$data['startDate']}-sd-{$data['endDate']}.xls";

        return response()
            ->view('laporan.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function monitoringStok()
    {
        $barangHabis = Barang::where('stok', '<=', 0)->get();
        $barangMenipis = Barang::where('stok', '>', 0)->where('stok', '<=', 5)->get();
        $barangAman = Barang::where('stok', '>', 5)->get();

        return view('laporan.monitoring_stok', compact(
            'barangHabis',
            'barangMenipis',
            'barangAman'
        ));
    }
}
