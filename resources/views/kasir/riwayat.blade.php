{{-- ════════════════════════════════════════════════════════════════
     RIWAYAT PENJUALAN — Daftar transaksi kasir
     ════════════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Riwayat Penjualan — Shanum Elektrik')
@section('page-title', 'Riwayat Penjualan')
@section('breadcrumb', 'Shanum Elektrik / Penjualan / Riwayat')

@section('content')

{{-- Form pencarian & filter --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-5" style="border:1px solid #EDE5D4;">
    <form method="GET" action="{{ route('kasir.riwayat') }}" class="flex flex-col sm:flex-row gap-3">
        {{-- Input cari no invoice --}}
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" fill="none" stroke="#8a9bb5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari no. invoice..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm border focus:outline-none focus:ring-2"
                   style="border-color:#EDE5D4;">
        </div>

        {{-- Filter kategori --}}
        <select name="kategori_id"
                class="px-4 py-2.5 rounded-xl text-sm border focus:outline-none"
                style="border-color:#EDE5D4;">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors cursor-pointer"
                style="background-color:#162447; color:#F7F1E5;"
                onmouseover="this.style.backgroundColor='#1e3060';"
                onmouseout="this.style.backgroundColor='#162447';">
            Cari
        </button>

        @if(request('cari') || request('kategori_id'))
            <a href="{{ route('kasir.riwayat') }}"
               class="px-5 py-2.5 rounded-xl text-sm font-medium text-center transition-colors"
               style="border:1px solid #EDE5D4; color:#8a9bb5;"
               onmouseover="this.style.backgroundColor='#F7F1E5';"
               onmouseout="this.style.backgroundColor='';">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Tabel riwayat --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background-color:#F7F1E5;">
                    <th class="text-left px-5 py-3 font-semibold" style="color:#162447;">No. Invoice</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color:#162447;">Tanggal</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color:#162447;">Kasir</th>
                    <th class="text-right px-5 py-3 font-semibold" style="color:#162447;">Total</th>
                    <th class="text-right px-5 py-3 font-semibold" style="color:#162447;">Bayar</th>
                    <th class="text-center px-5 py-3 font-semibold" style="color:#162447;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:#EDE5D4;">
                @forelse($transaksis as $trx)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium" style="color:#162447;">{{ $trx->no_invoice }}</td>
                        <td class="px-5 py-3" style="color:#8a9bb5;">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3" style="color:#8a9bb5;">{{ $trx->kasir->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold" style="color:#162447;">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right" style="color:#8a9bb5;">Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('kasir.show', $trx) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                               style="background-color:#F7F1E5; color:#162447;"
                               onmouseover="this.style.backgroundColor='#EDE5D4';"
                               onmouseout="this.style.backgroundColor='#F7F1E5';">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center" style="color:#8a9bb5;">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transaksis->hasPages())
        <div class="px-5 py-4" style="border-top:1px solid #EDE5D4;">
            {{ $transaksis->links() }}
        </div>
    @endif
</div>

@endsection
