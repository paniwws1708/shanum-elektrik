{{-- ════════════════════════════════════════════════════════════════
     RIWAYAT TRANSAKSI — Daftar transaksi kasir / admin
     ════════════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Riwayat Transaksi — Shanum Elektrik')
@section('page-title', 'Riwayat Transaksi')
@section('breadcrumb', 'Shanum Elektrik / Penjualan / Riwayat')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Riwayat Transaksi</h1>
    <p class="mt-1 text-sm" style="color:#5a7398;">
        Tampilan Admin: semua transaksi penjualan. Kasir hanya melihat transaksinya sendiri.
    </p>
</div>

{{-- Main Card (Filter + Table + Pagination) --}}
<div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">

    {{-- Form pencarian & filter --}}
    <form method="GET" action="{{ route('kasir.riwayat') }}" class="mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
        <div class="lg:col-span-4">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Cari transaksi</label>
            <input type="text"
                   name="cari"
                   value="{{ request('cari') }}"
                   placeholder="No. invoice atau nama barang"
                   class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                   style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                   onfocus="this.style.borderColor='#162447';"
                   onblur="this.style.borderColor='#DDD3BE';">
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Filter kategori barang</label>
            <select name="kategori_id"
                    class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                    style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                <option value="">Semua kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" @selected(request('kategori_id') == $kat->id)>
                        {{ $kat->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Dari tanggal</label>
            <input type="date"
                   name="dari_tanggal"
                   value="{{ request('dari_tanggal') }}"
                   class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                   style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Sampai tanggal</label>
            <input type="date"
                   name="sampai_tanggal"
                   value="{{ request('sampai_tanggal') }}"
                   class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                   style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
        </div>

        <div class="lg:col-span-2 flex items-center gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2 rounded-xl text-sm font-semibold transition-colors cursor-pointer"
                    style="background-color:#EDE5D4; color:#162447;"
                    onmouseover="this.style.backgroundColor='#DDD3BE';"
                    onmouseout="this.style.backgroundColor='#EDE5D4';">
                Terapkan
            </button>

            @if(request()->hasAny(['cari', 'kategori_id', 'dari_tanggal', 'sampai_tanggal']))
                <a href="{{ route('kasir.riwayat') }}"
                   class="px-3 py-2 rounded-xl text-sm font-medium transition-colors"
                   style="border:1px solid #DDD3BE; color:#5a7398;"
                   onmouseover="this.style.backgroundColor='#FAF6EE';"
                   onmouseout="this.style.backgroundColor='';">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel riwayat --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                    <th class="pb-3 pr-3 font-medium">No. invoice</th>
                    <th class="pb-3 px-3 font-medium">Waktu</th>
                    <th class="pb-3 px-3 font-medium">Dilayani oleh</th>
                    <th class="pb-3 px-3 font-medium">Barang</th>
                    <th class="pb-3 px-3 font-medium text-right">Total</th>
                    <th class="pb-3 px-3 font-medium text-right">Bayar</th>
                    <th class="pb-3 px-3 font-medium text-right">Kembalian</th>
                    <th class="pb-3 px-3 font-medium text-center">Status</th>
                    <th class="pb-3 pl-3 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:#F7F1E5;">
                @forelse($transaksis as $trx)
                    @php
                        $roleLabel = match ($trx->kasir?->role) {
                            'admin'        => 'Admin',
                            'kasir'        => 'Kasir',
                            'staff_barang' => 'Staff Barang',
                            default        => null,
                        };
                        $firstItem = $trx->details->first();
                        $extraCount = max($trx->details->count() - 1, 0);
                    @endphp
                    <tr class="transition-colors"
                        onmouseover="this.style.backgroundColor='#FAF6EE';"
                        onmouseout="this.style.backgroundColor='';">
                        <td class="whitespace-nowrap py-3.5 pr-3 font-semibold" style="color:#162447;">
                            {{ $trx->no_invoice }}
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3" style="color:#5a7398;">
                            {{ $trx->created_at->format('d M, H.i') }}
                        </td>
                        <td class="py-3.5 px-3" style="color:#5a7398;">
                            {{ $trx->kasir->name ?? '-' }}@if($roleLabel) &middot; {{ $roleLabel }}@endif
                        </td>
                        <td class="py-3.5 px-3" style="color:#162447;">
                            {{ $firstItem?->nama_barang ?? '-' }}
                            @if($extraCount > 0)
                                <span style="color:#5a7398;">+ {{ $extraCount }} lainnya</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 text-right font-bold tabular-nums" style="color:#162447;">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 text-right tabular-nums" style="color:#5a7398;">
                            Rp {{ number_format($trx->bayar, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 text-right tabular-nums" style="color:#5a7398;">
                            Rp {{ number_format($trx->kembalian, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                  style="background-color:#dcfce7; color:#166534;">
                                Selesai
                            </span>
                        </td>
                        <td class="whitespace-nowrap py-3.5 pl-3 text-center">
                            <a href="{{ route('kasir.show', $trx) }}"
                               class="text-xs font-semibold underline"
                               style="color:#162447;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-sm" style="color:#8a9bb5;">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transaksis->hasPages())
        <div class="mt-4 pt-4" style="border-top:1px solid #EDE5D4;">
            {{ $transaksis->links() }}
        </div>
    @endif
</div>

@endsection
