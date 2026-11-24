
@extends('layouts.app')

@section('title', 'Tambah Barang — Shanum Elektrik')
@section('page-title', 'Tambah Barang')
@section('breadcrumb', 'Inventaris / Barang / Tambah')


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
        <form action="{{ route('inventaris.barang.store') }}"
              method="POST"
              enctype="multipart/form-data"
              x-data="{ preview: null }"
              class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang"
                       value="{{ old('kode_barang') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" required />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                       value="{{ old('nama_barang') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" required />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id"
                        class="w-full border rounded px-3 py-2"
                        style="border-color:#DDD3BE;" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" id="harga_jual"
                       value="{{ old('harga_jual') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" step="0.01" required />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="harga_modal">Harga Modal</label>
                <input type="number" name="harga_modal" id="harga_modal"
                       value="{{ old('harga_modal') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" step="0.01" required />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="stok">Stok Awal</label>
                <input type="number" name="stok" id="stok"
                       value="{{ old('stok') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" min="0" required />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="stok_minimum">Stok Minimum</label>
                <input type="number" name="stok_minimum" id="stok_minimum"
                       value="{{ old('stok_minimum', 10) }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" min="0" />
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" id="lokasi_penyimpanan"
                       value="{{ old('lokasi_penyimpanan') }}"
                       class="w-full border rounded px-3 py-2"
                       style="border-color:#DDD3BE;" />
            </div>



{{-- Foto Barang dan Tombol --}}
<div style="grid-column: 1 / -1; width: 100%;">

    <label class="block text-sm font-medium mb-2" for="foto">
        Foto (opsional)
    </label>

    <input
        type="file"
        name="foto"
        id="foto"
        accept="image/*"
        class="foto-input w-full text-sm text-gray-700"
        @change="
            preview = $event.target.files.length
                ? URL.createObjectURL($event.target.files[0])
                : null
        "
    />

    {{-- Preview Foto --}}
    <template x-if="preview">
        <img
            :src="preview"
            alt="Pratinjau Foto"
            class="mt-3 h-24 w-24 rounded-lg object-cover border"
            style="border-color:#EDE5D4;"
        />
    </template>

    {{-- Tombol --}}
    <div style="
        display: flex;
        justify-content: flex-end;
        align-items: center;
        width: 100%;
        margin-top: 20px;
        gap: 12px;
    ">

        <button
            type="submit"
            style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 94px;
                height: 42px;
                flex: 0 0 94px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                color: white;
                background-color: #162447;
            ">
            Simpan
        </button>

        <a
            href="{{ route('inventaris.barang.index') }}"
            style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 80px;
                height: 42px;
                flex: 0 0 80px;
                border: 1px solid #d8cbb5;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                color: #162447;
                background-color: white;
            ">
            Batal
        </a>

    </div>

</div>

@endsection
