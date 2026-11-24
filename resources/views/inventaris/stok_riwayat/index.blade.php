@extends('layouts.app')

@section('title', 'Riwayat Stok — Shanum Elektrik')
@section('page-title', 'Riwayat Stok')
@section('breadcrumb', 'Inventaris / Riwayat Stok')

@section('content')
<div class="mb-5">
    <h2 class="text-xl font-bold" style="color:#162447;">Riwayat Perubahan Stok</h2>
    <p class="mt-1 text-sm text-gray-500">
        Semua aktivitas perubahan stok barang, termasuk barang masuk, barang keluar, dan penyesuaian stok.
    </p>
</div>


<form method="GET"
      class="mb-4 flex flex-wrap items-center gap-3 rounded-xl bg-white p-4 shadow-sm">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Cari nama atau kode barang"
        class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
    >

    <select
        name="tipe"
        class="rounded-lg border border-gray-300 px-3 py-2 text-sm"
    >
        <option value="">Semua Jenis</option>
        <option value="masuk" {{ $tipe === 'masuk' ? 'selected' : '' }}>
            Barang Masuk
        </option>
        <option value="keluar" {{ $tipe === 'keluar' ? 'selected' : '' }}>
            Barang Keluar
        </option>
        <option value="penyesuaian" {{ $tipe === 'penyesuaian' ? 'selected' : '' }}>
            Penyesuaian
        </option>
    </select>

    <button
        type="submit"
        class="rounded-lg px-4 py-2 text-sm font-semibold text-white"
        style="background:#162447;"
    >
        Cari
    </button>

    @if($search || $tipe)
        <a
            href="{{ route('inventaris.stok_riwayat.index') }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm"
        >
            Reset
        </a>
    @endif
</form>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3 text-right">Perubahan</th>
                    <th class="px-4 py-3 text-right">Stok Sebelum</th>
                    <th class="px-4 py-3 text-right">Stok Sesudah</th>
                    <th class="px-4 py-3">Petugas</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($riwayats as $riwayat)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                            {{ $riwayat->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-gray-800">
                            {{ $riwayat->barang->nama_barang ?? 'Barang dihapus' }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $riwayat->tipe === 'masuk'
                                    ? 'bg-green-100 text-green-700'
                                    : ($riwayat->tipe === 'keluar'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($riwayat->tipe) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right font-semibold
                            {{ $riwayat->jumlah_perubahan >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ $riwayat->jumlah_perubahan > 0 ? '+' : '' }}{{ number_format($riwayat->jumlah_perubahan) }}
                        </td>

                        <td class="px-4 py-3 text-right text-gray-600">
                            {{ number_format($riwayat->stok_sebelum) }}
                        </td>

                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            {{ number_format($riwayat->stok_sesudah) }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $riwayat->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $riwayat->keterangan ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            Belum ada riwayat perubahan stok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $riwayats->links() }}
</div>
@endsection