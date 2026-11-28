@extends('layouts.app')

@section('title', 'Keuangan — Shanum Elektrik')
@section('page-title', 'Keuangan')
@section('breadcrumb', 'Administrasi / Keuangan')

@section('content')
{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Keuangan</h1>
        <p class="mt-1 text-sm" style="color:#5a7398;">
            Pemasukan dan pengeluaran toko &middot; {{ now()->translatedFormat('F Y') }}
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('keuangan.pemasukan.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm"
           style="background-color:#162447; color:#F7F1E5;"
           onmouseover="this.style.backgroundColor='#1e3060';"
           onmouseout="this.style.backgroundColor='#162447';">
            + Catat pemasukan
        </a>
        <a href="{{ route('keuangan.pengeluaran.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors"
           style="border:1.5px solid #162447; background-color:#ffffff; color:#162447;"
           onmouseover="this.style.backgroundColor='#FAF6EE';"
           onmouseout="this.style.backgroundColor='#ffffff';">
            + Catat pengeluaran
        </a>
    </div>
</div>

{{-- 4 Kartu Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Pemasukan bulan ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#166534;">
            Rp {{ number_format($ringkasan['pemasukan'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">penjualan dan sumber lain</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Pengeluaran bulan ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#991b1b;">
            Rp {{ number_format($ringkasan['pengeluaran'], 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">biaya operasional toko</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Penjualan hari ini</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums" style="color:#162447;">
            Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">dari {{ $transaksiHariIniCount }} transaksi</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <p class="text-xs font-medium" style="color:#5a7398;">Keuntungan bersih</p>
        <p class="mt-2 text-2xl sm:text-3xl font-bold tabular-nums"
           style="color:{{ $keuntunganBersih >= 0 ? '#162447' : '#991b1b' }};">
            Rp {{ number_format($keuntunganBersih, 0, ',', '.') }}
        </p>
        <p class="mt-1.5 text-xs" style="color:#8a9bb5;">setelah modal dan pengeluaran</p>
    </div>

</div>

{{-- Pemasukan terbaru & Pengeluaran terbaru --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Pemasukan terbaru --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <h2 class="text-base font-bold mb-4" style="color:#162447;">Pemasukan terbaru</h2>

        <div class="divide-y" style="border-color:#F7F1E5;">
            @forelse ($pemasukanTerbaru as $p)
                <div class="py-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:#162447;">
                            {{ $p->keterangan ?: (\App\Models\Pemasukan::SUMBER[$p->sumber] ?? $p->sumber) }}
                        </p>
                        <div class="mt-1 flex flex-wrap items-center gap-1.5 text-xs" style="color:#8a9bb5;">
                            <span>{{ $p->tanggal->format('d M Y') }}</span>
                            <span>&middot;</span>
                            @if($p->sumber === 'penjualan_harian')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium"
                                      style="background-color:#dbeafe; color:#1d4ed8;">
                                    Otomatis dari transaksi
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium"
                                      style="background-color:#EDE5D4; color:#5a7398;">
                                    Sumber lain
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="text-sm font-bold tabular-nums whitespace-nowrap" style="color:#166534;">
                        + Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <div class="py-8 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada catatan pemasukan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pengeluaran terbaru --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">
        <h2 class="text-base font-bold mb-4" style="color:#162447;">Pengeluaran terbaru</h2>

        <div class="divide-y" style="border-color:#F7F1E5;">
            @forelse ($pengeluaranTerbaru as $pg)
                <div class="py-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:#162447;">
                            {{ $pg->keterangan ?: (\App\Models\Pengeluaran::KATEGORI[$pg->kategori] ?? $pg->kategori) }}
                        </p>
                        <p class="mt-1 text-xs" style="color:#8a9bb5;">
                            {{ $pg->tanggal->format('d M Y') }}
                        </p>
                    </div>
                    <span class="text-sm font-bold tabular-nums whitespace-nowrap" style="color:#991b1b;">
                        &minus; Rp {{ number_format($pg->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <div class="py-8 text-center text-sm" style="color:#8a9bb5;">
                    Belum ada catatan pengeluaran.
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Catatan Kaki --}}
<p class="text-xs leading-relaxed" style="color:#8a9bb5;">
    Pemasukan penjualan terhubung langsung ke data transaksi, sedangkan pemasukan lain dan pengeluaran dicatat manual oleh Admin. Keduanya dipakai untuk laporan keuangan.
</p>
@endsection
