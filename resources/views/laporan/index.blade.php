@extends('layouts.app')

@section('title', 'Laporan Penjualan & Keuangan')
@section('page-title', 'Laporan Penjualan & Keuangan')
@section('breadcrumb', 'Admin / Laporan Toko')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:#162447;">Jenis Periode</label>
                <select name="periode" onchange="this.form.submit()" class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 focus:outline-none">
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Mingguan (Minggu Ini)</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan (Bulan Ini)</option>
                    <option value="custom" {{ $periode == 'custom' ? 'selected' : '' }}>Pilih Tanggal Manual</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1" style="color:#162447;">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       onchange="document.querySelector('select[name=periode]').value='custom'"
                       class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300">
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1" style="color:#162447;">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       onchange="document.querySelector('select[name=periode]').value='custom'"
                       class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-lg text-sm font-semibold text-white cursor-pointer" style="background-color:#162447;">
                    Tampilkan
                </button>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank"
                   class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700">
                    Cetak PDF
                </a>
                <a href="{{ route('admin.laporan.excel', request()->query()) }}"
                   class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700">
                    Ekspor Excel
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
            <p class="text-xs font-semibold uppercase" style="color:#8a9bb5;">Total Transaksi</p>
            <p class="text-2xl font-bold mt-1" style="color:#162447;">{{ number_format($totalTransaksi) }} Transaksi</p>
            <p class="text-xs mt-1 text-gray-500">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
            <p class="text-xs font-semibold uppercase" style="color:#8a9bb5;">Total Omzet (Pemasukan)</p>
            <p class="text-2xl font-bold mt-1 text-emerald-600">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            <p class="text-xs mt-1 text-gray-500">Modal Terjual: Rp {{ number_format($totalHargaModalTerjual, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
            <p class="text-xs font-semibold uppercase" style="color:#8a9bb5;">Total Pengeluaran (Stok)</p>
            <p class="text-2xl font-bold mt-1 text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            <p class="text-xs mt-1 text-gray-500">{{ $barangMasuks->count() }} riwayat barang masuk</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
            <p class="text-xs font-semibold uppercase" style="color:#8a9bb5;">Keuntungan Bersih</p>
            <p class="text-2xl font-bold mt-1 {{ $keuntunganBersih >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                Rp {{ number_format($keuntunganBersih, 0, ',', '.') }}
            </p>
            <p class="text-xs mt-1 text-gray-500">Omzet - Modal - Pengeluaran</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
        <div class="px-5 py-4 border-b" style="border-color:#EDE5D4;">
            <h2 class="text-sm font-bold" style="color:#162447;">Rincian Pemasukan (Transaksi Penjualan)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead style="background-color:#F7F1E5; color:#162447;">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No. Invoice</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Kasir</th>
                        <th class="px-4 py-3 font-semibold text-right">Total Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaksis as $trx)
                        <tr>
                            <td class="px-4 py-3 font-medium" style="color:#162447;">{{ $trx->no_invoice }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $trx->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-400">Tidak ada transaksi penjualan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
        <div class="px-5 py-4 border-b" style="border-color:#EDE5D4;">
            <h2 class="text-sm font-bold" style="color:#162447;">Rincian Pengeluaran (Pembelian / Barang Masuk)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead style="background-color:#F7F1E5; color:#162447;">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Nama Barang</th>
                        <th class="px-4 py-3 font-semibold">Supplier</th>
                        <th class="px-4 py-3 font-semibold text-center">Jumlah</th>
                        <th class="px-4 py-3 font-semibold text-right">Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barangMasuks as $bm)
                        <tr>
                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium" style="color:#162447;">{{ $bm->barang->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $bm->supplier->nama_supplier ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">{{ $bm->jumlah }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600">
                                Rp {{ number_format($bm->jumlah * ($bm->harga_modal_satuan ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Tidak ada pengeluaran barang masuk pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection