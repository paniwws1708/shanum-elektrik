@extends('layouts.app')

@section('title', 'Data Barang — Shanum Elektrik')
@section('page-title', 'Data Barang')
@section('breadcrumb', 'Inventaris / Barang')

@section('content')
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold" style="color:#162447;">Daftar Barang</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola informasi barang dan pantau kondisi stok.</p>
        </div>
        <a href="{{ route('inventaris.barang.create') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm" style="background-color:#162447;">+ Tambah Barang</a>
    </div>

    <form method="GET" action="{{ route('inventaris.barang.index') }}" class="mb-4 flex flex-wrap gap-2 rounded-xl bg-white p-4 shadow-sm">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang" class="min-w-56 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">

        <select name="kategori_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>

        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            <option value="menipis" @selected(request('status') === 'menipis')>Menipis</option>
            <option value="habis" @selected(request('status') === 'habis')>Habis</option>
            <option value="aman" @selected(request('status') === 'aman')>Aman</option>
        </select>

        <button class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color:#162447;">Terapkan</button>

        @if(request()->hasAny(['search', 'kategori_id', 'status']))
            <a href="{{ route('inventaris.barang.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600">Reset</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-left">Kode Barang</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Lokasi Penyimpanan</th>
                        <th class="px-4 py-3 text-right">Harga Modal</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-center">Stok</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($barangs as $barang)
                        @php($stok = (int) $barang->stok)

                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3">
                                @if($barang->foto)
                                    <img
                                        src="{{ asset('storage/' . $barang->foto) }}"
                                        alt="{{ $barang->nama_barang }}"
                                        class="h-12 w-12 rounded-lg border border-gray-200 object-cover"
                                        loading="lazy"
                                        onerror="this.style.display='none';"
                                    >
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-[10px] text-gray-400">
                                        No Foto
                                    </div>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-700">
                                {{ $barang->kode_barang ?: '-' }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $barang->nama_barang }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $barang->kategori->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $barang->lokasi_penyimpanan ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700">
    Rp{{ number_format($barang->harga_modal, 0, ',', '.') }}
</td>

                            <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700">
                                Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                @if($stok <= 0)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Stok Habis
                                    </span>
                                @elseif($stok <= (int) $barang->stok_minimum)
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Stok Menipis
                                    </span>
                                @else
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Stok Aman
                                    </span>
                                @endif

                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $stok }} unit · Min. {{ $barang->stok_minimum }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                <a href="{{ route('inventaris.barang.edit', $barang) }}" class="font-medium text-blue-700 hover:underline">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('inventaris.barang.destroy', $barang) }}"
                                    method="POST"
                                    class="ml-2 inline"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="font-medium text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $barangs->links() }}
    </div>
@endsection