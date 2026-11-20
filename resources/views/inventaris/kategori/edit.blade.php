@extends('layouts.app')

@section('title', 'Edit Barang — Shanum Elektrik')
@section('page-title', 'Edit Barang')
@section('breadcrumb', 'Inventaris / Barang / Edit')

@section('content')
@php
    $kategoriList = $kategoris ?? \App\Models\Kategori::all();
    $inputClass = 'w-full rounded-lg px-3 py-2 text-sm bg-white';
    $inputStyle = 'border:1px solid #DDD3BE; color:#162447;';
    $fotoLama = isset($barang) && $barang->foto ? asset('storage/'.$barang->foto) : null;
@endphp

<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm p-6" style="border:1px solid #EDE5D4;">

        @if($errors->any())
            <div class="mb-5 text-sm px-4 py-3 rounded-xl" style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
                <p class="font-semibold mb-1">Data belum bisa disimpan:</p>
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('inventaris.barang.update', $barang) }}" enctype="multipart/form-data"
              x-data="{ preview: @js($fotoLama) }" class="space-y-5">
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="kode_barang" class="block text-sm font-semibold mb-1" style="color:#162447;">Kode Barang</label>
                    <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required
                           class="{{ $inputClass }}" style="{{ $inputStyle }}">
                    @error('kode_barang')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-semibold mb-1" style="color:#162447;">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                           class="{{ $inputClass }}" style="{{ $inputStyle }}">
                    @error('nama_barang')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-semibold mb-1" style="color:#162447;">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required class="{{ $inputClass }}" style="{{ $inputStyle }}">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" @selected((string) old('kategori_id', $barang->kategori_id) === (string) $k->id)>
                                {{ $k->nama ?? $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="lokasi_penyimpanan" class="block text-sm font-semibold mb-1" style="color:#162447;">Lokasi Penyimpanan <span class="font-normal" style="color:#8a9bb5;">(opsional)</span></label>
                    <input type="text" id="lokasi_penyimpanan" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $barang->lokasi_penyimpanan) }}"
                           class="{{ $inputClass }}" style="{{ $inputStyle }}" placeholder="Mis. Rak A-1">
                    @error('lokasi_penyimpanan')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="harga_modal" class="block text-sm font-semibold mb-1" style="color:#162447;">Harga Modal (Rp)</label>
                    <input type="number" id="harga_modal" name="harga_modal" value="{{ old('harga_modal', $barang->harga_modal) }}" step="0.01" min="0" required
                           class="{{ $inputClass }}" style="{{ $inputStyle }}">
                    @error('harga_modal')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="harga_jual" class="block text-sm font-semibold mb-1" style="color:#162447;">Harga Jual (Rp)</label>
                    <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" step="0.01" min="0" required
                           class="{{ $inputClass }}" style="{{ $inputStyle }}">
                    @error('harga_jual')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color:#162447;">Stok Saat Ini</label>
                    <input type="text" value="{{ $barang->stok }}" readonly
                           class="{{ $inputClass }}" style="border:1px solid #DDD3BE; background-color:#F7F1E5; color:#162447;">
                    <p class="text-xs mt-1" style="color:#8a9bb5;">Stok diubah lewat menu Barang Masuk agar riwayatnya tercatat.</p>
                </div>

                <div>
                    <label for="stok_minimum" class="block text-sm font-semibold mb-1" style="color:#162447;">Stok Minimum</label>
                    <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0"
                           class="{{ $inputClass }}" style="{{ $inputStyle }}">
                    <p class="text-xs mt-1" style="color:#8a9bb5;">Stok di bawah atau sama dengan angka ini ditandai menipis.</p>
                    @error('stok_minimum')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-semibold mb-1" style="color:#162447;">Foto Barang <span class="font-normal" style="color:#8a9bb5;">(opsional, jpg/png/webp, maks 2 MB)</span></label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp"
                           @change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)"
                           class="w-full text-sm" style="color:#162447;">
                    @error('foto')<p class="text-xs mt-1" style="color:#dc2626;">{{ $message }}</p>@enderror
                    <template x-if="preview">
                        <img :src="preview" alt="Pratinjau foto" class="mt-3 h-28 w-28 object-cover rounded-lg" style="border:1px solid #DDD3BE;">
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold cursor-pointer"
                        style="background-color:#162447; color:#F7F1E5;">Simpan Perubahan</button>
                <a href="{{ route('inventaris.barang.index') }}" class="px-5 py-2 rounded-lg text-sm font-semibold"
                   style="border:1px solid #DDD3BE; color:#162447;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
