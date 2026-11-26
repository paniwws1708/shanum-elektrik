@extends('layouts.app')

@section('title', 'Laporan Penjualan & Keuangan')

@section('content')
<div class="p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Penjualan & Keuangan</h1>
        <p class="text-sm text-slate-500">Admin / Laporan Toko</p>
    </div>

    {{-- Filter & Tombol Ekspor --}}
    <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Periode</label>
                <select name="periode" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $startDate }}" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $endDate }}" class="w-full rounded-lg border-slate-300 text-sm">
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg text-sm">
                    Tampilkan
                </button>
                <a href="{{ route('admin.laporan.pdf', ['dari' => $startDate, 'sampai' => $endDate]) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-sm text-center">
                    Cetak PDF
                </a>
                <a href="{{ route('admin.laporan.excel', ['dari' => $startDate, 'sampai' => $endDate]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg text-sm text-center">
                    Ekspor Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Kartu Ringkasan Keuangan (Dari KeuanganService) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Pemasukan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($ringkasan['pemasukan'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">
                Penjualan: Rp {{ number_format($ringkasan['pemasukan_penjualan'], 0, ',', '.') }} |
                Lainnya: Rp {{ number_format($ringkasan['pemasukan_lainnya'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($ringkasan['pengeluaran'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $daftarPengeluaran->count() }} catatan pengeluaran</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase">Saldo / Arus Kas</p>
            <p class="text-2xl font-bold {{ $ringkasan['saldo'] >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                Rp {{ number_format($ringkasan['saldo'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-500 mt-1">Pemasukan - Pengeluaran</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase">Pengeluaran Per Kategori</p>
            @if(count($ringkasan['pengeluaran_per_kategori']) > 0)
                <ul class="mt-2 space-y-1 text-xs text-slate-700">
                    @foreach($ringkasan['pengeluaran_per_kategori'] as $kategori => $total)
                        <li class="flex justify-between">
                            <span class="capitalize">{{ str_replace('_', ' ', $kategori) }}</span>
                            <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-xs text-slate-400 mt-2">Belum ada pengeluaran</p>
            @endif
        </div>
    </div>

    {{-- Tabel Rincian Pemasukan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-200 font-bold text-slate-800">Rincian Pemasukan</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Sumber</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($daftarPemasukan as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->tanggal ?? $item->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $item->sumber ?? '-') }}</td>
                            <td class="px-4 py-3">{{ $item->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-400">Tidak ada data pemasukan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Rincian Pengeluaran --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-200 font-bold text-slate-800">Rincian Pengeluaran</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($daftarPengeluaran as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->tanggal ?? $item->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $item->kategori ?? '-') }}</td>
                            <td class="px-4 py-3">{{ $item->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-400">Tidak ada data pengeluaran pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection