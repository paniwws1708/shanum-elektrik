@extends('layouts.app')

@section('title', 'Monitoring Stok — Shanum Elektrik')
@section('page-title', 'Monitoring Kondisi Stok Barang')
@section('breadcrumb', 'Inventaris / Monitoring Stok')

@section('content')
{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Monitoring Stok</h1>
    <p class="mt-1 text-sm" style="color:#5a7398;">
        Pantau kondisi persediaan barang berdasarkan batas minimum dan pergerakan penjualan
    </p>
</div>

<div class="space-y-6">

    {{-- 4 Kartu Filter Kondisi Stok --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'semua']) }}"
           class="bg-white rounded-2xl p-5 transition-all"
           style="border:{{ $filterStatus === 'semua' ? '2px solid #162447' : '1px solid #EDE5D4' }};">
            <p class="text-xs font-medium" style="color:#5a7398;">Semua barang</p>
            <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
                {{ $stokMenipisCount + $stokSedangCount + $stokBanyakCount }} item
            </p>
            <p class="mt-1.5 text-xs" style="color:#8a9bb5;">total inventaris</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'menipis']) }}"
           class="bg-white rounded-2xl p-5 transition-all"
           style="border:{{ $filterStatus === 'menipis' ? '2px solid #991b1b' : '1px solid #EDE5D4' }};">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium" style="color:#5a7398;">Stok menipis</p>
                <span class="w-2.5 h-2.5 rounded-full" style="background-color:#dc2626;"></span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#b91c1c;">
                {{ $stokMenipisCount }} barang
            </p>
            <p class="mt-1.5 text-xs" style="color:#8a9bb5;">stok &le; batas minimum</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'sedang']) }}"
           class="bg-white rounded-2xl p-5 transition-all"
           style="border:{{ $filterStatus === 'sedang' ? '2px solid #b45309' : '1px solid #EDE5D4' }};">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium" style="color:#5a7398;">Stok sedang</p>
                <span class="w-2.5 h-2.5 rounded-full" style="background-color:#d97706;"></span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#b45309;">
                {{ $stokSedangCount }} barang
            </p>
            <p class="mt-1.5 text-xs" style="color:#8a9bb5;">perlu dipantau</p>
        </a>

        <a href="{{ route('inventaris.monitoring_stok.index', ['status' => 'banyak']) }}"
           class="bg-white rounded-2xl p-5 transition-all"
           style="border:{{ $filterStatus === 'banyak' ? '2px solid #166534' : '1px solid #EDE5D4' }};">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium" style="color:#5a7398;">Stok banyak</p>
                <span class="w-2.5 h-2.5 rounded-full" style="background-color:#16a34a;"></span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#166534;">
                {{ $stokBanyakCount }} barang
            </p>
            <p class="mt-1.5 text-xs" style="color:#8a9bb5;">persediaan aman</p>
        </a>
    </div>

    {{-- Top 5 Terlaris & Jarang Terjual --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
            <h2 class="text-base font-bold mb-4" style="color:#162447;">Top 5 barang paling sering terjual</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                            <th class="pb-2.5 font-medium">Nama barang</th>
                            <th class="pb-2.5 font-medium text-center">Sisa stok</th>
                            <th class="pb-2.5 font-medium text-right">Total terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:#F7F1E5;">
                        @forelse($barangTerlaris as $item)
                            <tr>
                                <td class="py-3 pr-3 font-medium" style="color:#162447;">{{ $item->nama_barang }}</td>
                                <td class="py-3 px-2 text-center tabular-nums" style="color:#5a7398;">{{ $item->stok }}</td>
                                <td class="py-3 pl-2 text-right font-bold tabular-nums" style="color:#166534;">{{ $item->total_terjual }} pcs</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-sm" style="color:#8a9bb5;">Belum ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
            <h2 class="text-base font-bold mb-4" style="color:#162447;">Top 5 barang paling jarang terjual</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                            <th class="pb-2.5 font-medium">Nama barang</th>
                            <th class="pb-2.5 font-medium text-center">Sisa stok</th>
                            <th class="pb-2.5 font-medium text-right">Total terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:#F7F1E5;">
                        @forelse($barangJarangTerjual as $item)
                            <tr>
                                <td class="py-3 pr-3 font-medium" style="color:#162447;">{{ $item->nama_barang }}</td>
                                <td class="py-3 px-2 text-center tabular-nums" style="color:#5a7398;">{{ $item->stok }}</td>
                                <td class="py-3 pl-2 text-right font-semibold tabular-nums" style="color:#5a7398;">{{ $item->total_terjual }} pcs</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-sm" style="color:#8a9bb5;">Belum ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar Kondisi Stok Barang --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold" style="color:#162447;">
                Daftar kondisi stok barang ({{ ucfirst($filterStatus) }})
            </h2>
        </div>

        <div class="overflow-x-auto overflow-y-auto max-h-96">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 bg-white z-10">
                    <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                        <th class="pb-3 pr-3 font-medium">Kode</th>
                        <th class="pb-3 px-3 font-medium">Nama barang</th>
                        <th class="pb-3 px-3 font-medium">Kategori</th>
                        <th class="pb-3 px-3 font-medium text-center">Stok saat ini</th>
                        <th class="pb-3 px-3 font-medium text-center">Stok minimum</th>
                        <th class="pb-3 pl-3 font-medium text-center">Indikator status</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#F7F1E5;">
                    @forelse($barangs as $barang)
                        <tr class="transition-colors"
                            onmouseover="this.style.backgroundColor='#FAF6EE';"
                            onmouseout="this.style.backgroundColor='';">
                            <td class="whitespace-nowrap py-3.5 pr-3 text-xs font-medium" style="color:#8a9bb5;">
                                {{ $barang->kode_barang ?: '-' }}
                            </td>
                            <td class="py-3.5 px-3 font-semibold" style="color:#162447;">
                                {{ $barang->nama_barang }}
                            </td>
                            <td class="py-3.5 px-3" style="color:#5a7398;">
                                {{ $barang->kategori->nama ?? '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold tabular-nums" style="color:#162447;">
                                {{ $barang->stok }}
                            </td>
                            <td class="py-3.5 px-3 text-center tabular-nums" style="color:#8a9bb5;">
                                {{ $barang->stok_minimum }}
                            </td>
                            <td class="py-3.5 pl-3 text-center">
                                @if($barang->stok <= $barang->stok_minimum)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                          style="background-color:#fee2e2; color:#991b1b;">
                                        Stok Menipis
                                    </span>
                                @elseif($barang->stok <= ($barang->stok_minimum * 2))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                          style="background-color:#fef3c7; color:#92400e;">
                                        Stok Sedang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                          style="background-color:#dcfce7; color:#166534;">
                                        Stok Banyak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm" style="color:#8a9bb5;">
                                Tidak ada data barang pada kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
