
@extends('layouts.app')

@section('title', 'Data Kategori — Shanum Elektrik')
@section('page-title', 'Data Kategori')
@section('breadcrumb', 'Inventaris / Kategori')

@section('content')
<div class="space-y-5">

    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold" style="color:#162447;">
                Daftar Kategori
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola kategori barang untuk memudahkan pengelompokan inventaris.
            </p>
        </div>

        <a href="{{ route('inventaris.kategori.create') }}"
           class="inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
           style="background-color:#162447;">
            + Tambah Kategori
        </a>
    </div>

    {{-- Tabel kategori --}}
    <div class="overflow-hidden rounded-xl border bg-white shadow-sm"
         style="border-color:#EDE5D4;">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-50">
    <tr class="border-b border-gray-200">
        <th class="px-5 py-4 text-left font-semibold text-gray-600">
            NAMA KATEGORI
        </th>

        <th class="px-5 py-4 text-center font-semibold text-gray-600"
            style="width: 180px;">
            AKSI
        </th>
    </tr>
</thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoris as $kategori)
                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $kategori->nama }}
                            </td>

                            <td class="px-5 py-4 text-center" style="width: 180px;">
    <div class="inline-flex items-center justify-center gap-3">

        <a href="{{ route('inventaris.kategori.edit', $kategori) }}"
           class="font-semibold text-blue-600 hover:text-blue-800">
            Edit
        </a>

        <form action="{{ route('inventaris.kategori.destroy', $kategori) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="font-semibold text-red-600 hover:text-red-800">
                Hapus
            </button>
        </form>

    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-gray-500">
                                Belum ada kategori barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
