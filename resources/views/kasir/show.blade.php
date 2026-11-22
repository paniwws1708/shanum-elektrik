{{-- ════════════════════════════════════════════════════════════════
     DETAIL / STRUK TRANSAKSI
     ════════════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Struk ' . $transaksi->no_invoice . ' — Shanum Elektrik')
@section('page-title', 'Detail Transaksi')
@section('breadcrumb', 'Shanum Elektrik / Penjualan / Riwayat / ' . $transaksi->no_invoice)

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Tombol kembali --}}
    <div class="mb-4">
        <a href="{{ route('kasir.riwayat') }}"
           class="inline-flex items-center text-sm font-medium transition-colors"
           style="color:#8a9bb5;"
           onmouseover="this.style.color='#162447';"
           onmouseout="this.style.color='#8a9bb5';">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>

    {{-- Kartu struk --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">

        {{-- Header struk --}}
        <div class="px-6 py-5 text-center" style="background-color:#162447;">
            <h2 class="text-lg font-bold" style="color:#F7F1E5;">Shanum Elektrik</h2>
            <p class="text-xs mt-1" style="color:#8a9bb5;">Struk Pembayaran</p>
        </div>

        {{-- Info transaksi --}}
        <div class="px-6 py-4 space-y-2" style="border-bottom:1px solid #EDE5D4;">
            <div class="flex justify-between text-sm">
                <span style="color:#8a9bb5;">No. Invoice</span>
                <span class="font-semibold" style="color:#162447;">{{ $transaksi->no_invoice }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span style="color:#8a9bb5;">Tanggal</span>
                <span style="color:#162447;">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span style="color:#8a9bb5;">Kasir</span>
                <span style="color:#162447;">{{ $transaksi->kasir->name ?? '-' }}</span>
            </div>
        </div>

        {{-- Daftar item --}}
        <div class="px-6 py-4" style="border-bottom:1px solid #EDE5D4;">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #EDE5D4;">
                        <th class="text-left py-2 font-semibold" style="color:#162447;">Barang</th>
                        <th class="text-center py-2 font-semibold" style="color:#162447;">Qty</th>
                        <th class="text-right py-2 font-semibold" style="color:#162447;">Harga</th>
                        <th class="text-right py-2 font-semibold" style="color:#162447;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->details as $detail)
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td class="py-2" style="color:#162447;">{{ $detail->nama_barang }}</td>
                            <td class="py-2 text-center" style="color:#8a9bb5;">{{ $detail->jumlah }}</td>
                            <td class="py-2 text-right" style="color:#8a9bb5;">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-2 text-right font-medium" style="color:#162447;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Ringkasan pembayaran --}}
        <div class="px-6 py-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span style="color:#8a9bb5;">Total</span>
                <span class="font-bold text-base" style="color:#162447;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span style="color:#8a9bb5;">Bayar</span>
                <span class="font-semibold" style="color:#162447;">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm px-3 py-2 rounded-xl" style="background-color:#ecfdf5;">
                <span style="color:#059669;" class="font-medium">Kembalian</span>
                <span class="font-bold" style="color:#059669;">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 text-center" style="background-color:#F7F1E5;">
            <p class="text-xs" style="color:#8a9bb5;">Terima kasih telah berbelanja di Shanum Elektrik!</p>
        </div>
    </div>
</div>

@endsection
