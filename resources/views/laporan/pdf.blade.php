<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Shanum Elektrik</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; margin: 20px; }
        h2, h3 { margin: 0 0 5px 0; color: #162447; }
        .header { text-align: center; border-bottom: 2px solid #162447; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 7px 10px; text-align: left; }
        th { background-color: #162447; color: #fff; }
        .text-right { text-align: right; }
        .summary-box { background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding:8px 15px; background:#162447; color:#fff; border:none; border-radius:5px; cursor:pointer;">
            Cetak / Simpan sebagai PDF
        </button>
    </div>

    <div class="header">
        <h2>SHANUM ELEKTRIK</h2>
        <p>Laporan Penjualan dan Keuangan Toko</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="summary-box">
        <strong>Ringkasan Keuangan:</strong><br>
        Total Transaksi: {{ $totalTransaksi }} |
        Total Omzet: Rp {{ number_format($totalOmzet, 0, ',', '.') }} |
        Modal Terjual: Rp {{ number_format($totalHargaModalTerjual, 0, ',', '.') }} |
        Pengeluaran Stok: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }} |
        <strong>Keuntungan Bersih: Rp {{ number_format($keuntunganBersih, 0, ',', '.') }}</strong>
    </div>

    <h3>Data Penjualan (Pemasukan)</h3>
    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th class="text-right">Total Omzet</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $trx)
            <tr>
                <td>{{ $trx->no_invoice }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Data Pengeluaran (Barang Masuk)</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th class="text-right">Total Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangMasuks as $bm)
            <tr>
                <td>{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d/m/Y') }}</td>
                <td>{{ $bm->barang->nama_barang ?? '-' }}</td>
                <td>{{ $bm->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($bm->jumlah * ($bm->harga_modal_satuan ?? 0), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>