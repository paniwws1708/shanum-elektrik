@extends('layouts.app')

@section('title', 'Monitoring Kondisi Stok')
@section('page-title', 'Monitoring Kondisi Stok Barang')
@section('breadcrumb', 'Inventaris / Monitoring Stok')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'semua']) }}"
           class="bg-white rounded-xl p-5 shadow-sm transition {{ $filterStatus == 'semua' ? 'ring-2 ring-blue-900' : '' }}"
           style="border:1px solid #EDE5D4;">
            <p class="text-xs font-semibold uppercase text-gray-500">Semua Barang</p>
            <p class="text-2xl font-bold mt-1" style="color:#162447;">{{ $stokMenipisCount + $stokSedangCount + $stokBanyakCount }} Item</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'menipis']) }}"
           class="bg-red-50 rounded-xl p-5 shadow-sm border border-red-200 transition {{ $filterStatus == 'menipis' ? 'ring-2 ring-red-600' : '' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase text-red-700">Stok Menipis (Merah)</p>
                <span class="w-3 h-3 rounded-full bg-red-600"></span>
            </div>
            <p class="text-2xl font-bold mt-1 text-red-700">{{ $stokMenipisCount }} Barang</p>
            <p class="text-xs text-red-600 mt-1">Stok &le; batas minimum</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'sedang']) }}"
           class="bg-amber-50 rounded-xl p-5 shadow-sm border border-amber-200 transition {{ $filterStatus == 'sedang' ? 'ring-2 ring-amber-500' : '' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase text-amber-700">Stok Sedang (Kuning)</p>
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
            </div>
            <p class="text-2xl font-bold mt-1 text-amber-700">{{ $stokSedangCount }} Barang</p>
            <p class="text-xs text-amber-600 mt-1">Perlu dipantau</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'banyak']) }}"
           class="bg-emerald-50 rounded-xl p-5 shadow-sm border border-emerald-200 transition {{ $filterStatus == 'banyak' ? 'ring-2 ring-emerald-600' : '' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase text-emerald-700">Stok Banyak (Hijau)</p>
                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
            </div>
            <p class="text-2xl font-bold mt-1 text-emerald-700">{{ $stokBanyakCount }} Barang</p>
            <p class="text-xs text-emerald-600 mt-1">Persediaan aman</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
            <div class="px-5 py-4 border-b" style="border-color:#EDE5D4;">
                <h3 class="text-sm font-bold text-emerald-700">Top 5 Barang Paling Sering Terjual</h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead style="background-color:#F7F1E5; color:#162447;">
                    <tr>
                        <th class="px-4 py-2.5">Nama Barang</th>
                        <th class="px-4 py-2.5 text-center">Sisa Stok</th>
                        <th class="px-4 py-2.5 text-right">Total Terjual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($barangTerlaris as $item)
                        <tr>
                            <td class="px-4 py-2.5 font-medium" style="color:#162447;">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-2.5 text-center">{{ $item->stok }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-emerald-600">{{ $item->total_terjual }} pcs</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
            <div class="px-5 py-4 border-b" style="border-color:#EDE5D4;">
                <h3 class="text-sm font-bold text-amber-700">Top 5 Barang Paling Jarang Terjual</h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead style="background-color:#F7F1E5; color:#162447;">
                    <tr>
                        <th class="px-4 py-2.5">Nama Barang</th>
                        <th class="px-4 py-2.5 text-center">Sisa Stok</th>
                        <th class="px-4 py-2.5 text-right">Total Terjual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($barangJarangTerjual as $item)
                        <tr>
                            <td class="px-4 py-2.5 font-medium" style="color:#162447;">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-2.5 text-center">{{ $item->stok }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-gray-500">{{ $item->total_terjual }} pcs</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
        <div class="px-5 py-4 border-b flex justify-between items-center" style="border-color:#EDE5D4;">
            <h2 class="text-sm font-bold" style="color:#162447;">Daftar Kondisi Stok Barang ({{ ucfirst($filterStatus) }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead style="background-color:#F7F1E5; color:#162447;">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Kode</th>
                        <th class="px-4 py-3 font-semibold">Nama Barang</th>
                        <th class="px-4 py-3 font-semibold">Kategori</th>
                        <th class="px-4 py-3 font-semibold text-center">Stok Saat Ini</th>
                        <th class="px-4 py-3 font-semibold text-center">Stok Minimum</th>
                        <th class="px-4 py-3 font-semibold text-center">Indikator Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barangs as $barang)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">{{ $barang->kode_barang }}</td>
                            <td class="px-4 py-3 font-medium" style="color:#162447;">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $barang->stok }}</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $barang->stok_minimum }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($barang->stok <= $barang->stok_minimum)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Stok Menipis
                                    </span>
                                @elseif($barang->stok <= ($barang->stok_minimum * 2))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Stok Sedang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Stok Banyak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">Tidak ada data barang pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection