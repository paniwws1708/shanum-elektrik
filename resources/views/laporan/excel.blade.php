<table border="1">
    <tr>
        <th colspan="4" style="font-size:14px; font-weight:bold;">LAPORAN PENJUALAN & KEUANGAN - SHANUM ELEKTRIK</th>
    </tr>
    <tr>
        <td colspan="4">Periode: {{ $startDate }} s/d {{ $endDate }}</td>
    </tr>
    <tr></tr>
    <tr>
        <th>Total Transaksi</th>
        <th>Total Omzet</th>
        <th>Total Pengeluaran Stok</th>
        <th>Keuntungan Bersih</th>
    </tr>
    <tr>
        <td>{{ $totalTransaksi }}</td>
        <td>{{ $totalOmzet }}</td>
        <td>{{ $totalPengeluaran }}</td>
        <td>{{ $keuntunganBersih }}</td>
    </tr>
    <tr></tr>
    <tr>
        <th colspan="4">RINCIAN PENJUALAN</th>
    </tr>
    <tr>
        <th>No Invoice</th>
        <th>Tanggal</th>
        <th>Kasir</th>
        <th>Total Harga</th>
    </tr>
    @foreach($transaksis as $trx)
    <tr>
        <td>{{ $trx->no_invoice }}</td>
        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
        <td>{{ $trx->user->name ?? '-' }}</td>
        <td>{{ $trx->total_harga }}</td>
    </tr>
    @endforeach
</table>