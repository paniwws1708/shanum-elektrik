@extends('layouts.app')

@section('title', 'Keuangan — Shanum Elektrik')
@section('page-title', 'Keuangan')
@section('breadcrumb', 'Administrasi / Keuangan')

@section('content')
<div class="space-y-4">
    @include('keuangan._header')

    {{-- Filter periode --}}
    <form method="GET" class="flex flex-wrap items-end gap-2">
        <div>
            <label class="block text-xs mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <button class="px-4 py-2 bg-slate-200 rounded-lg text-sm">Tampilkan</button>
    </form>

    {{-- 3 kartu angka. number_format(angka, 0, ',', '.') = format rupiah: 1.500.000 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-sm text-slate-500">Total Pemasukan</div>
            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($ringkasan['pemasukan'], 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-2">
                Penjualan: Rp {{ number_format($ringkasan['pemasukan_penjualan'], 0, ',', '.') }}<br>
                Lainnya: Rp {{ number_format($ringkasan['pemasukan_lainnya'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-sm text-slate-500">Total Pengeluaran</div>
            <div class="text-2xl font-bold text-red-600">Rp {{ number_format($ringkasan['pengeluaran'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-sm text-slate-500">Saldo (Pemasukan - Pengeluaran)</div>
            {{-- Hijau kalau plus, merah kalau minus --}}
            <div class="text-2xl font-bold {{ $ringkasan['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($ringkasan['saldo'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Pengeluaran per kategori --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-semibold mb-3">Pengeluaran per Kategori</h2>
        <table class="w-full text-sm">
            @forelse ($ringkasan['pengeluaran_per_kategori'] as $kategori => $total)
                <tr class="border-t">
                    {{-- Ubah kode kategori jadi tulisan; kalau tidak ada, tampilkan apa adanya --}}
                    <td class="py-2">{{ \App\Models\Pengeluaran::KATEGORI[$kategori] ?? $kategori }}</td>
                    <td class="py-2 text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="py-4 text-center text-slate-500">Belum ada pengeluaran pada periode ini.</td></tr>
            @endforelse
        </table>
    </div>
</div>
@endsection