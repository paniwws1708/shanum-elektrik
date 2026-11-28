@extends('layouts.app')

@section('title', 'Dashboard Admin — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Admin')

@section('content')
@php
    $maxJuta = max((float) ($penjualan7Hari->max('juta') ?? 0), 0.1);
@endphp

{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Dashboard Admin</h1>
        <p class="mt-1 text-sm" style="color:#5a7398;">
            Laporan keseluruhan toko &middot; {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    <a href="{{ Route::has('admin.laporan.index') ? route('admin.laporan.index') : '#' }}"
       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm"
       style="background-color:#162447; color:#F7F1E5;"
       onmouseover="this.style.backgroundColor='#1e3060';"
       onmouseout="this.style.backgroundColor='#162447';">
        Lihat laporan lengkap
    </a>
</div>

{{-- Stats Cards (4 kolom) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Omzet bulan ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            Rp {{ number_format($stats['omzet_bulan_ini'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">
            dari {{ $stats['transaksi_bulan_ini'] }} transaksi
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Keuntungan bersih</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums"
           style="color:{{ $stats['keuntungan_bersih'] >= 0 ? '#162447' : '#b91c1c' }};">
            Rp {{ number_format($stats['keuntungan_bersih'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">setelah modal dan pengeluaran</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Transaksi hari ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            {{ $stats['transaksi_hari_ini'] }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">
            senilai Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Stok menipis</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#b91c1c;">
            {{ $stats['stok_menipis'] }} barang
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">
            {{ $stats['stok_habis'] }} di antaranya habis
        </p>
    </div>

</div>

{{-- Middle Row: Penjualan 7 hari terakhir & Restock menunggu --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Penjualan 7 hari terakhir (2/3) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-semibold" style="color:#162447;">Penjualan 7 hari terakhir</h2>
            <span class="text-xs" style="color:#8a9bb5;">dalam juta rupiah</span>
        </div>

        <div class="grid grid-cols-7 gap-3 items-end h-44 pt-4">
            @foreach($penjualan7Hari as $hari)
                @php
                    $persen = $hari['juta'] > 0 ? max(round(($hari['juta'] / $maxJuta) * 100), 8) : 4;
                @endphp
                <div class="flex flex-col items-center justify-end h-full">
                    <span class="text-xs font-medium mb-1.5 tabular-nums" style="color:#162447;">
                        {{ number_format($hari['juta'], 1, ',', '.') }}
                    </span>
                    <div class="w-full max-w-[42px] rounded-t-lg transition-all"
                         style="height:{{ $persen }}%; background-color:{{ $hari['is_today'] ? '#b8862d' : '#162447' }};"></div>
                    <span class="mt-2.5 text-xs {{ $hari['is_today'] ? 'font-bold' : '' }}"
                          style="color:{{ $hari['is_today'] ? '#162447' : '#8a9bb5' }};">
                        {{ $hari['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Restock menunggu (1/3) --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold" style="color:#162447;">Restock menunggu</h2>
            <a href="#" class="text-xs font-medium underline" style="color:#162447;">Lihat semua</a>
        </div>

        <div class="divide-y flex-1" style="border-color:#F7F1E5;">
            @forelse($restockMenunggu as $restock)
                <div class="py-3 flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold" style="color:#162447;">{{ $restock->nama_barang }}</p>
                        <p class="text-xs mt-0.5" style="color:#8a9bb5;">{{ $restock->jumlah }} &middot; {{ $restock->pengaju }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium shrink-0"
                          style="background-color:#eff6ff; color:#1d4ed8;">
                        {{ $restock->status }}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada pengajuan restock yang menunggu.
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Bottom Row: Paling sering terjual & Paling jarang terjual --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Paling sering terjual --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <h2 class="text-base font-semibold mb-4" style="color:#162447;">Paling sering terjual</h2>
        <div class="divide-y" style="border-color:#F7F1E5;">
            @forelse($palingSeringTerjual as $item)
                <div class="py-2.5 flex items-center justify-between gap-3 text-sm">
                    <span style="color:#162447;">{{ $item->nama_barang }}</span>
                    <span class="font-semibold tabular-nums shrink-0" style="color:#162447;">
                        {{ number_format($item->total_terjual, 0, ',', '.') }} pcs
                    </span>
                </div>
            @empty
                <div class="py-6 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada data penjualan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Paling jarang terjual --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <h2 class="text-base font-semibold mb-4" style="color:#162447;">Paling jarang terjual</h2>
        <div class="divide-y" style="border-color:#F7F1E5;">
            @forelse($palingJarangTerjual as $item)
                <div class="py-2.5 flex items-center justify-between gap-3 text-sm">
                    <span style="color:#162447;">{{ $item->nama_barang }}</span>
                    <span class="font-semibold tabular-nums shrink-0" style="color:#162447;">
                        {{ number_format($item->total_terjual, 0, ',', '.') }} pcs
                    </span>
                </div>
            @empty
                <div class="py-6 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada data penjualan.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
