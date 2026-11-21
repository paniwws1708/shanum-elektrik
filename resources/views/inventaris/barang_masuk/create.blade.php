@extends('layouts.app')

@section('title', 'Catat Barang Masuk — Shanum Elektrik')
@section('page-title', 'Catat Barang Masuk')
@section('breadcrumb', 'Inventaris / Barang Masuk / Tambah')

@section('content')
<div class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow-sm">
    <h2 class="mb-1 text-lg font-bold" style="color:#162447;">Form Barang Masuk</h2>
    <p class="mb-6 text-sm text-gray-500">
        Setelah disimpan, jumlah stok barang akan langsung bertambah dan tercatat di riwayat stok.
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inventaris.barang_masuk.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Barang <span class="text-red-500">*</span>
                </label>

                <select name="barang_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    <option value="">Pilih barang</option>

                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" @selected(old('barang_id') == $barang->id)>
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Supplier</label>

                <select name="supplier_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    <option value="">Tanpa supplier</option>

                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                            {{ $supplier->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Tanggal Masuk <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="tanggal_masuk"
                    value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Jumlah Barang <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="jumlah"
                    min="1"
                    step="1"
                    value="{{ old('jumlah', 1) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Harga Modal per Unit (Rp)
                </label>

                <input
                    type="number"
                    name="harga_modal_satuan"
                    min="0"
                    step="0.01"
                    value="{{ old('harga_modal_satuan') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    placeholder="Opsional"
                >
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700">Catatan</label>

                <textarea
                    name="catatan"
                    rows="3"
                    maxlength="500"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    placeholder="Contoh: Pembelian stok dari supplier"
                >{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('inventaris.barang_masuk.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600">
                Batal
            </a>

            <button class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background:#162447;">
                Simpan & Tambah Stok
            </button>
        </div>
    </form>
</div>
@endsection