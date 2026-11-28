@extends('layouts.app')

@section('title', 'Dashboard Kasir — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Kasir')

@section('content')
@php
    $maxTotal = max((float) ($laporan7Hari->max('total') ?? 0), 1);
@endphp

{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Dashboard Kasir</h1>
        <p class="mt-1 text-sm" style="color:#5a7398;">
            Laporan penjualan Anda &middot; {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    <a href="{{ route('kasir.pos') }}"
       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm"
       style="background-color:#162447; color:#F7F1E5;"
       onmouseover="this.style.backgroundColor='#1e3060';"
       onmouseout="this.style.backgroundColor='#162447';">
        Transaksi baru
    </a>
</div>

{{-- Stats Cards (4 kolom) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Penjualan hari ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">sampai pukul {{ now()->format('H.i') }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Transaksi hari ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            {{ $stats['transaksi_hari_ini'] }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">semua berstatus selesai</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Rata-rata per transaksi</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            Rp {{ number_format($stats['rata_rata'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">hari ini</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">7 hari terakhir</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            Rp {{ number_format($stats['penjualan_7_hari'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">dari {{ $stats['transaksi_7_hari'] }} transaksi</p>
    </div>

</div>

{{-- Main Grid: Laporan penjualan 7 hari terakhir (2/3) & Transaksi terakhir (1/3) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Laporan penjualan 7 hari terakhir --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold" style="color:#162447;">Laporan penjualan 7 hari terakhir</h2>
            <span class="text-xs" style="color:#8a9bb5;">per hari</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                        <th class="pb-2.5 font-medium">Hari</th>
                        <th class="pb-2.5 font-medium text-center">Transaksi</th>
                        <th class="pb-2.5 font-medium">Grafik</th>
                        <th class="pb-2.5 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#F7F1E5;">
                    @foreach($laporan7Hari as $row)
                        @php($persen = $row['total'] > 0 ? max(round(($row['total'] / $maxTotal) * 100), 6) : 0)
                        <tr>
                            <td class="py-3 pr-3 {{ $row['is_today'] ? 'font-bold' : '' }}" style="color:#162447;">
                                {{ $row['hari'] }}
                            </td>
                            <td class="py-3 px-2 text-center tabular-nums {{ $row['is_today'] ? 'font-bold' : '' }}" style="color:#162447;">
                                {{ $row['jumlah_transaksi'] }}
                            </td>
                            <td class="py-3 px-3 w-48 sm:w-64">
                                <div class="w-full h-2.5 rounded-full overflow-hidden" style="background-color:#EDE5D4;">
                                    <div class="h-full rounded-full transition-all"
                                         style="width:{{ $persen }}%; background-color:{{ $row['is_today'] ? '#b8862d' : '#162447' }};"></div>
                                </div>
                            </td>
                            <td class="py-3 pl-2 text-right tabular-nums {{ $row['is_today'] ? 'font-bold' : '' }}" style="color:#162447;">
                                Rp {{ number_format($row['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transaksi terakhir --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold" style="color:#162447;">Transaksi terakhir</h2>
            <a href="{{ route('kasir.riwayat') }}" class="text-xs font-medium underline" style="color:#162447;">Lihat riwayat</a>
        </div>

        <div class="divide-y flex-1" style="border-color:#F7F1E5;">
            @forelse($transaksiTerakhir as $trx)
                <a href="{{ route('kasir.show', $trx) }}"
                   class="py-3 flex items-center justify-between gap-2 transition-colors hover:opacity-80">
                    <div>
                        <p class="text-sm font-semibold" style="color:#162447;">{{ $trx->no_invoice }}</p>
                        <p class="text-xs mt-0.5" style="color:#8a9bb5;">{{ $trx->created_at->format('H.i') }}</p>
                    </div>
                    <span class="text-sm font-bold tabular-nums" style="color:#162447;">
                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                    </span>
                </a>
            @empty
                <div class="py-10 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada transaksi.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
