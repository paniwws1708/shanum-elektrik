
@extends('layouts.app')

@section('title', 'Edit Kategori — Shanum Elektrik')
@section('page-title', 'Edit Kategori')
@section('breadcrumb', 'Inventaris / Kategori / Edit')

@section('content')
@php
    $inputClass = 'w-full rounded-lg px-3 py-2 text-sm bg-white';
    $inputStyle = 'border:1px solid #DDD3BE; color:#162447;';
@endphp

<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm p-6"
         style="border:1px solid #EDE5D4;">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-lg font-bold" style="color:#162447;">
                Ubah Data Kategori
            </h2>
            <p class="text-sm mt-1" style="color:#8a9bb5;">
                Perbarui informasi kategori barang.
            </p>
        </div>

        {{-- Error Validation --}}
        @if($errors->any())
            <div class="mb-5 text-sm px-4 py-3 rounded-xl"
                 style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">

                <p class="font-semibold mb-1">
                    Data belum bisa disimpan:
                </p>

                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit Kategori --}}
        <form method="POST"
              action="{{ route('inventaris.kategori.update', $kategori) }}"
              class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Nama Kategori --}}
            <div>
                <label for="nama"
                       class="block text-sm font-semibold mb-1"
                       style="color:#162447;">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $kategori->nama) }}"
                    required
                    maxlength="255"
                    placeholder="Masukkan nama kategori"
                    class="{{ $inputClass }}"
                    style="{{ $inputStyle }}"
                >

                @error('nama')
                    <p class="text-xs mt-1" style="color:#dc2626;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg text-sm font-semibold cursor-pointer"
                    style="background-color:#162447; color:#F7F1E5;"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('inventaris.kategori.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold"
                    style="border:1px solid #DDD3BE; color:#162447;"
                >
                    Batal
                </a>

            </div>
        </form>
    </div>
</div>
@endsection
