@extends('layouts.app')

@section('title', 'Dashboard Staff Barang — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Staff Barang')

@section('content')
{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Dashboard Staff Barang</h1>
        <p class="mt-1 text-sm" style="color:#5a7398;">
            Kondisi stok barang &middot; {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    <a href="#"
       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm"
       style="background-color:#162447; color:#F7F1E5;"
       onmouseover="this.style.backgroundColor='#1e3060';"
       onmouseout="this.style.backgroundColor='#162447';">
        Ajukan restock
    </a>
</div>

{{-- Stats Cards (4 kolom) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Jenis barang</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">{{ $stats['total_produk'] }}</p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">dalam {{ $stats['total_kategori'] }} kategori</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Stok menipis</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#b91c1c;">{{ $stats['stok_menipis'] }} barang</p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">perlu diperhatikan</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Stok habis</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#b91c1c;">{{ $stats['stok_habis'] }} barang</p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">stok sama dengan nol</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Pengajuan restock aktif</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">{{ $stats['restock_aktif'] }}</p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">0 diajukan, 0 diproses</p>
    </div>

</div>

{{-- Main Grid: Barang yang perlu perhatian (2/3) & Pengajuan saya (1/3) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Barang yang perlu perhatian --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <h2 class="text-base font-semibold mb-3" style="color:#162447;">Barang yang perlu perhatian</h2>

        {{-- Legenda indikator --}}
        <div class="flex flex-wrap gap-2 mb-4 text-xs font-medium">
            <span class="px-2.5 py-1 rounded-full" style="background-color:#fee2e2; color:#991b1b;">
                Merah: 5 atau kurang
            </span>
            <span class="px-2.5 py-1 rounded-full" style="background-color:#fef3c7; color:#92400e;">
                Kuning: 6 sampai 20
            </span>
            <span class="px-2.5 py-1 rounded-full" style="background-color:#dcfce7; color:#166534;">
                Hijau: lebih dari 20
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                        <th class="pb-2.5 font-medium">Barang</th>
                        <th class="pb-2.5 font-medium">Kategori</th>
                        <th class="pb-2.5 font-medium text-center">Stok</th>
                        <th class="pb-2.5 font-medium text-center">Indikator</th>
                        <th class="pb-2.5 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#F7F1E5;">
                    @forelse($barangPerhatian as $barang)
                        @php($stok = (int) $barang->stok)
                        <tr>
                            <td class="py-3 pr-3 font-semibold" style="color:#162447;">{{ $barang->nama_barang }}</td>
                            <td class="py-3 pr-3" style="color:#5a7398;">{{ $barang->kategori->nama ?? '-' }}</td>
                            <td class="py-3 px-2 text-center font-bold tabular-nums" style="color:#162447;">{{ $stok }}</td>
                            <td class="py-3 px-2 text-center">
                                @if($stok <= 0)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fee2e2; color:#991b1b;">Habis</span>
                                @elseif($stok <= 5)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fee2e2; color:#991b1b;">Menipis</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fef3c7; color:#92400e;">Sedang</span>
                                @endif
                            </td>
                            <td class="py-3 pl-2 text-right">
                                <a href="{{ Route::has('inventaris.barang_masuk.create') ? route('inventaris.barang_masuk.create') : '#' }}"
                                   class="text-xs font-semibold underline" style="color:#162447;">
                                    Ajukan restock
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm" style="color:#8a9bb5;">
                                Semua stok barang dalam kondisi aman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pengajuan saya --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold" style="color:#162447;">Pengajuan saya</h2>
            <a href="#" class="text-xs font-medium underline" style="color:#162447;">Lihat semua</a>
        </div>

        <div class="divide-y flex-1" style="border-color:#F7F1E5;">
            @forelse($pengajuanSaya as $pengajuan)
                <div class="py-3 flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold" style="color:#162447;">{{ $pengajuan->nama_barang }}</p>
                        <p class="text-xs mt-0.5" style="color:#8a9bb5;">{{ $pengajuan->jumlah }} &middot; {{ $pengajuan->tanggal }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium shrink-0"
                          style="background-color:#eff6ff; color:#1d4ed8;">
                        {{ $pengajuan->status }}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada pengajuan restock.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
