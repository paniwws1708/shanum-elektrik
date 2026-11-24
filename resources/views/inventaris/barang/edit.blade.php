
@extends('layouts.app')

@section('title', 'Edit Barang — Shanum Elektrik')
@section('page-title', 'Edit Barang')
@section('breadcrumb', 'Inventaris / Barang / Edit')


<style>
    .foto-input::file-selector-button {
        background-color: #F3F4F6;
        color: #374151;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        padding: 9px 16px;
        margin-right: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .foto-input::file-selector-button:hover {
        background-color: #E5E7EB;
    }
</style>

@section('content')
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded border" style="border-color:#EDE5D4;">
        <form
            action="{{ route('inventaris.barang.update', $barang) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{ preview: null }"
            class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4"
        >
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1" for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang"
                    value="{{ old('kode_barang', $barang->kode_barang) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" required />
                @error('kode_barang')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                    value="{{ old('nama_barang', $barang->nama_barang) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" required />
                @error('nama_barang')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" id="harga_jual"
                    value="{{ old('harga_jual', $barang->harga_jual) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" step="0.01" required />
                @error('harga_jual')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="harga_modal">Harga Modal</label>
                <input type="number" name="harga_modal" id="harga_modal"
                    value="{{ old('harga_modal', $barang->harga_modal) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" step="0.01" required />
                @error('harga_modal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="stok">Stok Saat Ini</label>
                <input type="number" name="stok" id="stok"
                    value="{{ $barang->stok }}"
                    class="w-full border rounded px-3 py-2 bg-gray-100"
                    style="border-color:#DDD3BE;" readonly />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="stok_minimum">Stok Minimum</label>
                <input type="number" name="stok_minimum" id="stok_minimum"
                    value="{{ old('stok_minimum', $barang->stok_minimum) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" min="0" />
                @error('stok_minimum')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" id="lokasi_penyimpanan"
                    value="{{ old('lokasi_penyimpanan', $barang->lokasi_penyimpanan) }}"
                    class="w-full border rounded px-3 py-2"
                    style="border-color:#DDD3BE;" />
                @error('lokasi_penyimpanan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Barang --}}
            <div style="grid-column: 1 / -1;">
                <label class="block text-sm font-medium mb-2" for="foto">
                    Foto (kosongkan bila tidak diganti)
                </label>

                @if($barang->foto)
                    <div class="mb-3">
                        <img
                            src="{{ asset('storage/' . $barang->foto) }}"
                            alt="{{ $barang->nama_barang }}"
                            class="h-24 w-24 rounded-lg object-cover"
                        >
                    </div>
                @endif

                <input
    type="file"
    name="foto"
    id="foto"
    accept="image/*"
    class="foto-input w-full text-sm text-gray-700"
/>

                @error('foto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div
                class="flex justify-end gap-3 pt-5"
                style="grid-column: 1 / -1; align-self: start;"
            >
                <button
                    type="submit"
                    class="rounded-lg px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
                    style="background-color:#162447;"
                >
                    Simpan
                </button>

                <a
                    href="{{ route('inventaris.barang.index') }}"
                    class="rounded-lg border px-6 py-2.5 text-sm font-semibold transition hover:bg-gray-50"
                    style="background-color:#FFFFFF; color:#162447; border-color:#d8cbb5;"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
