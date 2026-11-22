
@extends('layouts.app')

@section('title', 'Penyesuaian Stok - Shanum Elektrik')
@section('page-title', 'Penyesuaian Stok')
@section('breadcrumb', 'Inventaris / Penyesuaian Stok')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold" style="color:#162447;">
            Riwayat Penyesuaian Stok
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Riwayat perbandingan stok sistem dengan hasil penghitungan fisik.
        </p>
    </div>

    <a href="{{ route('inventaris.penyesuaian_stok.create') }}"
       class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white"
       style="background:#162447;">
        + Penyesuaian Stok
    </a>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

<form method="GET" class="mb-4 flex flex-wrap gap-2 rounded-xl bg-white p-4 shadow-sm">
    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Cari nama barang, kode, atau keterangan..."
        class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
    >

    <button type="submit"
            class="rounded-lg px-4 py-2 text-sm font-semibold text-white"
            style="background:#162447;">
        Cari
    </button>

    @if($search !== '')
        <a href="{{ route('inventaris.penyesuaian_stok.index') }}"
           class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
            Reset
        </a>
    @endif
</form>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3 text-right">Stok Sistem</th>
                    <th class="px-4 py-3 text-right">Stok Fisik</th>
                    <th class="px-4 py-3 text-right">Selisih</th>
                    <th class="px-4 py-3">Dicatat Oleh</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($penyesuaians as $penyesuaian)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                            {{ $penyesuaian->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-800">
                                {{ $penyesuaian->barang->nama_barang ?? 'Barang dihapus' }}
                            </div>
                            @if($penyesuaian->barang)
                                <div class="text-xs text-gray-500">
                                    {{ $penyesuaian->barang->kode_barang }}
                                </div>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-gray-600">
                            {{ number_format($penyesuaian->stok_sebelum) }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-800">
                            {{ number_format($penyesuaian->stok_sesudah) }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right font-semibold">
                            @if($penyesuaian->jumlah_perubahan > 0)
                                <span class="text-green-700">
                                    +{{ number_format($penyesuaian->jumlah_perubahan) }}
                                </span>
                            @elseif($penyesuaian->jumlah_perubahan < 0)
                                <span class="text-red-700">
                                    {{ number_format($penyesuaian->jumlah_perubahan) }}
                                </span>
                            @else
                                <span class="text-gray-500">0</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $penyesuaian->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $penyesuaian->keterangan ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                            Belum ada riwayat penyesuaian stok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $penyesuaians->links() }}
</div>
@endsection
