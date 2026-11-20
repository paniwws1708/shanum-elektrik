@extends('layouts.app')

@section('title', 'Data Supplier — Shanum Elektrik')
@section('page-title', 'Data Supplier')
@section('breadcrumb', 'Inventaris / Supplier')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold" style="color:#162447;">Daftar Supplier</h2>
        <p class="mt-1 text-sm text-gray-500">Kelola data pemasok barang.</p>
    </div>

    <a href="{{ route('inventaris.supplier.create') }}"
       class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white"
       style="background:#162447;">
        + Tambah Supplier
    </a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-5 py-3">No.</th>
                    <th class="px-5 py-3">Nama Supplier</th>
                    <th class="px-5 py-3">Telepon</th>
                    <th class="px-5 py-3">Alamat</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-500">
                            {{ $suppliers->firstItem() + $loop->index }}
                        </td>

                        <td class="px-5 py-3 font-semibold text-gray-800">
                            {{ $supplier->nama }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $supplier->telepon ?: '-' }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $supplier->alamat ?: '-' }}
                        </td>

                        <td class="whitespace-nowrap px-5 py-3 text-center">
                            <a href="{{ route('inventaris.supplier.edit', $supplier) }}"
                               class="font-medium text-blue-700 hover:underline">
                                Edit
                            </a>

                            <form
                                action="{{ route('inventaris.supplier.destroy', $supplier) }}"
                                method="POST"
                                class="ml-2 inline"
                                onsubmit="return confirm('Hapus supplier ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="font-medium text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                            Belum ada data supplier.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $suppliers->links() }}
</div>
@endsection