@extends('layouts.app')

@section('title', 'Barang Masuk — Shanum Elektrik')
@section('page-title', 'Barang Masuk')
@section('breadcrumb', 'Inventaris / Barang Masuk')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold" style="color:#162447;">Riwayat Barang Masuk</h2>
        <p class="mt-1 text-sm text-gray-500">
            Catatan penerimaan barang dari supplier. Stok bertambah otomatis saat data disimpan.
        </p>
    </div>

    <a href="{{ route('inventaris.barang_masuk.create') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white" style="background:#162447;">
        + Catat Barang Masuk
    </a>
</div>

<form method="GET" class="mb-4 flex gap-2 rounded-xl bg-white p-4 shadow-sm">
    <input
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nama barang atau supplier"
        class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
    >

    <button class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background:#162447;">
        Cari
    </button>

    @if(request('search'))
        <a href="{{ route('inventaris.barang_masuk.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
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
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3 text-right">Jumlah</th>
                    <th class="px-4 py-3 text-right">Harga Modal</th>
                    <th class="px-4 py-3">Dicatat Oleh</th>
                    <th class="px-4 py-3">Catatan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($barangMasuks as $masuk)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                            {{ \Illuminate\Support\Carbon::parse($masuk->tanggal_masuk)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-gray-800">
                            {{ $masuk->barang->nama_barang ?? 'Barang dihapus' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $masuk->supplier->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-right font-semibold text-green-700">
                            +{{ number_format($masuk->jumlah) }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-gray-600">
                            {{ $masuk->harga_modal_satuan !== null ? 'Rp'.number_format($masuk->harga_modal_satuan, 0, ',', '.') : '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $masuk->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $masuk->catatan ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                            Belum ada catatan barang masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $barangMasuks->links() }}
</div>
@endsection