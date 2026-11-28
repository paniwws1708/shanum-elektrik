@extends('layouts.app')

@section('title', 'Data Barang — Shanum Elektrik')
@section('page-title', 'Data Barang')
@section('breadcrumb', 'Inventaris / Barang')

@section('content')
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Data Barang</h1>
            <p class="mt-1 text-sm" style="color:#5a7398;">Kelola barang, harga, lokasi penyimpanan, dan stok</p>
        </div>
        <a href="{{ route('inventaris.barang.create') }}"
           class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-colors"
           style="background-color:#162447; color:#F7F1E5;"
           onmouseover="this.style.backgroundColor='#1e3060';"
           onmouseout="this.style.backgroundColor='#162447';">
            + Tambah barang
        </a>
    </div>

    {{-- Main Card (Filter + Table + Pagination) --}}
    <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('inventaris.barang.index') }}" class="mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-5">
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Cari barang</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nama atau kode barang"
                       class="w-full rounded-xl px-3.5 py-2 text-sm focus:outline-none"
                       style="border:1.5px solid #DDD3BE; color:#162447; background-color:#ffffff;"
                       onfocus="this.style.borderColor='#162447';"
                       onblur="this.style.borderColor='#DDD3BE';">
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Filter kategori</label>
                <select name="kategori_id"
                        class="w-full rounded-xl px-3.5 py-2 text-sm focus:outline-none"
                        style="border:1.5px solid #DDD3BE; color:#162447; background-color:#ffffff;">
                    <option value="">Semua kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Kondisi stok</label>
                <select name="status"
                        class="w-full rounded-xl px-3.5 py-2 text-sm focus:outline-none"
                        style="border:1.5px solid #DDD3BE; color:#162447; background-color:#ffffff;">
                    <option value="">Semua</option>
                    <option value="menipis" @selected(request('status') === 'menipis')>Menipis</option>
                    <option value="habis" @selected(request('status') === 'habis')>Habis</option>
                    <option value="aman" @selected(request('status') === 'aman')>Aman</option>
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold transition-colors cursor-pointer"
                        style="background-color:#EDE5D4; color:#162447;"
                        onmouseover="this.style.backgroundColor='#DDD3BE';"
                        onmouseout="this.style.backgroundColor='#EDE5D4';">
                    Terapkan
                </button>

                @if(request()->hasAny(['search', 'kategori_id', 'status']))
                    <a href="{{ route('inventaris.barang.index') }}"
                       class="rounded-xl px-3 py-2 text-sm font-medium transition-colors"
                       style="border:1px solid #DDD3BE; color:#5a7398;"
                       onmouseover="this.style.backgroundColor='#FAF6EE';"
                       onmouseout="this.style.backgroundColor='';">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                        <th class="pb-3 pr-3 font-medium">Foto</th>
                        <th class="pb-3 px-3 font-medium">Kode</th>
                        <th class="pb-3 px-3 font-medium">Nama barang</th>
                        <th class="pb-3 px-3 font-medium">Kategori</th>
                        <th class="pb-3 px-3 font-medium text-right">Harga modal</th>
                        <th class="pb-3 px-3 font-medium text-right">Harga jual</th>
                        <th class="pb-3 px-3 font-medium text-center">Stok</th>
                        <th class="pb-3 px-3 font-medium">Lokasi</th>
                        <th class="pb-3 pl-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y" style="border-color:#F7F1E5;">
                    @forelse($barangs as $barang)
                        @php($stok = (int) $barang->stok)

                        <tr class="transition-colors"
                            onmouseover="this.style.backgroundColor='#FAF6EE';"
                            onmouseout="this.style.backgroundColor='';">
                            <td class="whitespace-nowrap py-3 pr-3">
                                @if($barang->foto)
                                    <img
                                        src="{{ asset('storage/' . $barang->foto) }}"
                                        alt="{{ $barang->nama_barang }}"
                                        class="h-10 w-10 rounded-xl object-cover"
                                        style="border:1px solid #EDE5D4;"
                                        loading="lazy"
                                        onerror="this.style.display='none';"
                                    >
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                         style="background-color:#F7F1E5; color:#5a7398;">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="whitespace-nowrap py-3 px-3 text-xs font-medium" style="color:#8a9bb5;">
                                {{ $barang->kode_barang ?: '-' }}
                            </td>

                            <td class="py-3 px-3 font-semibold" style="color:#162447;">
                                {{ $barang->nama_barang }}
                            </td>

                            <td class="py-3 px-3" style="color:#5a7398;">
                                {{ $barang->kategori->nama ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap py-3 px-3 text-right tabular-nums" style="color:#162447;">
                                Rp {{ number_format($barang->harga_modal, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap py-3 px-3 text-right tabular-nums" style="color:#162447;">
                                Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap py-3 px-3 text-center">
                                @if($stok <= 0)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                          style="background-color:#fee2e2; color:#991b1b;">
                                        {{ $stok }} Habis
                                    </span>
                                @elseif($stok <= (int) $barang->stok_minimum)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                          style="background-color:#fee2e2; color:#991b1b;">
                                        {{ $stok }} Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                          style="background-color:#dcfce7; color:#166534;">
                                        {{ $stok }} Aman
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-3" style="color:#5a7398;">
                                {{ $barang->lokasi_penyimpanan ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap py-3 pl-3 text-center">
                                <a href="{{ route('inventaris.barang.edit', $barang) }}"
                                   class="text-xs font-semibold underline"
                                   style="color:#162447;">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('inventaris.barang.destroy', $barang) }}"
                                    method="POST"
                                    class="ml-2.5 inline"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-xs font-semibold underline cursor-pointer"
                                            style="color:#b91c1c;">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-sm" style="color:#8a9bb5;">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($barangs->hasPages())
            <div class="mt-4 pt-4" style="border-top:1px solid #EDE5D4;">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>
@endsection
