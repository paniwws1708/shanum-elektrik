<?php
?>
@extends('layouts.app')

@section('title', 'Tambah Barang — Shanum Elektrik')
@section('page-title', 'Tambah Barang')
@section('breadcrumb', 'Inventaris / Barang / Tambah')

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
        <form action="{{ route('inventaris.barang.store') }}" method="POST" enctype="multipart/form-data" x-data="{ preview: null }" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1" for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" step="0.01" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="harga_modal">Harga Modal</label>
                <input type="number" name="harga_modal" id="harga_modal" value="{{ old('harga_modal') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" step="0.01" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="stok">Stok Awal</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" min="0" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="stok_minimum">Stok Minimum</label>
                <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', 10) }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" min="0" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" id="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan') }}" class="w-full border rounded px-3 py-2" style="border-color:#DDD3BE;" />
            </div>
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium mb-1" for="foto">Foto (opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="w-full" @change="
                    preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null
                "/>
                <template x-if="preview">
                    <img :src="preview" alt="Pratinjau Foto" class="mt-2 h-24 object-cover" />
                </template>
            </div>
            <div class="col-span-1 md:col-span-2 flex justify-end space-x-2 mt-4">
                <button type="submit" class="bg-[#162447] text-[#F7F1E5] px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('inventaris.barang.index') }}" class="border border-[#162447] text-[#162447] px-4 py-2 rounded">Batal</a>
            </div>
        </form>
    </div>
@endsection
