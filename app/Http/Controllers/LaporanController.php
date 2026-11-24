<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    private function getDataLaporan(Request $request)
    {
        $periode = $request->get('periode', 'harian');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($periode === 'harian') {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
        } elseif ($periode === 'mingguan') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        } elseif ($periode === 'bulanan') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } else {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::today()->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::today()->endOfDay();
        }

        $transaksis = Transaksi::with(['user', 'detailTransaksis.barang'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $totalTransaksi = $transaksis->count();
        $totalOmzet = $transaksis->sum('total_harga');

        $totalHargaModalTerjual = 0;
        foreach ($transaksis as $trx) {
            foreach ($trx->detailTransaksis as $detail) {
                $hargaModal = $detail->barang ? $detail->barang->harga_modal : 0;
                $totalHargaModalTerjual += ($hargaModal * $detail->jumlah);
            }
        }

        $barangMasuks = BarangMasuk::with(['barang', 'supplier'])
            ->whereBetween('tanggal_masuk', [$start->toDateString(), $end->toDateString()])
            ->latest('tanggal_masuk')
            ->get();

        $totalPengeluaran = $barangMasuks->sum(function ($item) {
            return $item->jumlah * ($item->harga_modal_satuan ?? 0);
        });

        $keuntunganBersih = $totalOmzet - $totalHargaModalTerjual - $totalPengeluaran;

        return [
            'periode' => $periode,
            'startDate' => $start->toDateString(),
            'endDate' => $end->toDateString(),
            'transaksis' => $transaksis,
            'barangMasuks' => $barangMasuks,
            'totalTransaksi' => $totalTransaksi,
            'totalOmzet' => $totalOmzet,
            'totalHargaModalTerjual' => $totalHargaModalTerjual,
            'totalPengeluaran' => $totalPengeluaran,
            'keuntunganBersih' => $keuntunganBersih,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getDataLaporan($request);
        return view('laporan.index', $data);
    }

    public function cetakPdf(Request $request)
    {
        $data = $this->getDataLaporan($request);
        return view('laporan.pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getDataLaporan($request);
        $filename = 'Laporan_Shanum_Elektrik_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.xls';

        return response()
            ->view('laporan.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function monitoringStok(Request $request)
    {
        $filterStatus = $request->get('status', 'semua');

        $semuaBarang = Barang::with('kategori')
            ->leftJoin('detail_transaksis', 'barangs.id', '=', 'detail_transaksis.barang_id')
            ->select(
                'barangs.*',
                DB::raw('COALESCE(SUM(detail_transaksis.jumlah), 0) as total_terjual')
            )
            ->groupBy(
                'barangs.id',
                'barangs.kode_barang',
                'barangs.nama_barang',
                'barangs.kategori_id',
                'barangs.harga_modal',
                'barangs.harga_jual',
                'barangs.stok',
                'barangs.stok_minimum',
                'barangs.lokasi_penyimpanan',
                'barangs.foto',
                'barangs.deleted_at',
                'barangs.created_at',
                'barangs.updated_at'
            )
            ->get();

        $stokMenipisCount = $semuaBarang->filter(fn($b) => $b->stok <= $b->stok_minimum)->count();
        $stokSedangCount  = $semuaBarang->filter(fn($b) => $b->stok > $b->stok_minimum && $b->stok <= ($b->stok_minimum * 2))->count();
        $stokBanyakCount  = $semuaBarang->filter(fn($b) => $b->stok > ($b->stok_minimum * 2))->count();

        $barangs = $semuaBarang->filter(function ($b) use ($filterStatus) {
            if ($filterStatus === 'menipis') {
                return $b->stok <= $b->stok_minimum;
            } elseif ($filterStatus === 'sedang') {
                return $b->stok > $b->stok_minimum && $b->stok <= ($b->stok_minimum * 2);
            } elseif ($filterStatus === 'banyak') {
                return $b->stok > ($b->stok_minimum * 2);
            }
            return true;
        })->sortBy('stok');

        $barangTerlaris = $semuaBarang->sortByDesc('total_terjual')->take(5);
        $barangJarangTerjual = $semuaBarang->sortBy('total_terjual')->take(5);

        return view('laporan.monitoring_stok', compact(
            'barangs',
            'filterStatus',
            'stokMenipisCount',
            'stokSedangCount',
            'stokBanyakCount',
            'barangTerlaris',
            'barangJarangTerjual'
        ));
    }
}