@extends('layouts.app')

@section('title', 'Penjualan Harian — Shanum Elektrik')
@section('page-title', 'Keuangan')
@section('breadcrumb', 'Administrasi / Keuangan / Penjualan Harian')

@section('content')
<div class="space-y-4">
    @include('keuangan._header')

    <p class="text-sm text-slate-600">
        Rekap otomatis dari transaksi kasir (30 hari terakhir). Klik
        <strong>Catat ke Pemasukan</strong> untuk memasukkan total hari itu ke pemasukan.
        Satu tanggal hanya bisa dicatat satu kali.
    </p>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Jumlah Transaksi</th>
                    <th class="p-3 text-right">Total Penjualan</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekap as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                        <td class="p-3">{{ $row->jumlah_transaksi }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                        <td class="p-3">
                            {{-- isset(...) = tanggal ini sudah ada di daftar yang sudah dicatat --}}
                            @if (isset($sudahDicatat[$row->tanggal]))
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Sudah dicatat</span>
                            @else
                                <form method="POST" action="{{ route('keuangan.penjualan-harian.catat') }}">
                                    @csrf
                                    {{-- Hanya mengirim tanggal. Total dihitung ulang oleh server. --}}
                                    <input type="hidden" name="tanggal" value="{{ $row->tanggal }}">
                                    <button class="px-3 py-1 bg-slate-900 text-white rounded-lg text-xs">Catat ke Pemasukan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-slate-500">Belum ada transaksi penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection