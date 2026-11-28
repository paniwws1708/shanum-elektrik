<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Services\KeuanganService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function monitoringStok(Request $request)
    {
        $filterStatus = $request->input('status', 'semua');
        if (! in_array($filterStatus, ['semua', 'menipis', 'sedang', 'banyak'], true)) {
            $filterStatus = 'semua';
        }

        $hasBarang = Schema::hasTable('barangs');
        $hasDetail = Schema::hasTable('detail_transaksis');

        $stokMenipisCount = 0;
        $stokSedangCount = 0;
        $stokBanyakCount = 0;
        $barangs = collect();

        if ($hasBarang) {
            $stokMenipisCount = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
            $stokSedangCount = Barang::whereColumn('stok', '>', 'stok_minimum')
                ->whereRaw('stok <= (stok_minimum * 2)')
                ->count();
            $stokBanyakCount = Barang::whereRaw('stok > (stok_minimum * 2)')->count();

            $query = Barang::with('kategori')->orderBy('stok');
            if ($filterStatus === 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimum');
            } elseif ($filterStatus === 'sedang') {
                $query->whereColumn('stok', '>', 'stok_minimum')
                    ->whereRaw('stok <= (stok_minimum * 2)');
            } elseif ($filterStatus === 'banyak') {
                $query->whereRaw('stok > (stok_minimum * 2)');
            }

            $barangs = $query->get();
        }

        $barangTerlaris = collect();
        $barangJarangTerjual = collect();

        if ($hasDetail && $hasBarang) {
            $barangTerlaris = DetailTransaksi::query()
                ->leftJoin('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
                ->select(
                    'detail_transaksis.nama_barang',
                    DB::raw('COALESCE(barangs.stok, 0) as stok'),
                    DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
                )
                ->groupBy('detail_transaksis.nama_barang', 'barangs.stok')
                ->orderByDesc('total_terjual')
                ->take(5)
                ->get();

            $barangJarangTerjual = DetailTransaksi::query()
                ->leftJoin('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
                ->select(
                    'detail_transaksis.nama_barang',
                    DB::raw('COALESCE(barangs.stok, 0) as stok'),
                    DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
                )
                ->groupBy('detail_transaksis.nama_barang', 'barangs.stok')
                ->orderBy('total_terjual')
                ->take(5)
                ->get();
        }

        return view('laporan.monitoring_stok', compact(
            'filterStatus',
            'stokMenipisCount',
            'stokSedangCount',
            'stokBanyakCount',
            'barangTerlaris',
            'barangJarangTerjual',
            'barangs'
        ));
    }
}
