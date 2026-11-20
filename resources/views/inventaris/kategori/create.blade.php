
@extends('layouts.app')

@section('title', 'Tambah Kategori — Shanum Elektrik')
@section('page-title', 'Tambah Kategori')
@section('breadcrumb', 'Inventaris / Kategori / Tambah')

@section('content')

<div class="max-w-2xl">

    <div class="bg-white rounded-2xl shadow-sm p-6"
         style="border:1px solid #EDE5D4;">

        <h2 class="text-lg font-semibold mb-5"
            style="color:#162447;">
            Form Tambah Kategori
        </h2>

        @if($errors->any())
            <div class="mb-5 bg-red-100 border border-red-300
                        text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventaris.kategori.store') }}"
              method="POST"
              class="space-y-5">

            @csrf

            <div>
                <label for="nama"
                       class="block text-sm font-semibold mb-2"
                       style="color:#162447;">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Kabel, Lampu, Saklar"
                    required
                    class="w-full rounded-lg px-3 py-2 text-sm"
                    style="border:1px solid #DDD3BE; color:#162447;"
                >

                @error('nama')
                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">

                <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-semibold"
                        style="background-color:#162447; color:#F7F1E5;">
                    Simpan Kategori
                </button>

                <a href="{{ route('inventaris.kategori.index') }}"
                   class="px-5 py-2 rounded-lg text-sm font-semibold"
                   style="border:1px solid #DDD3BE; color:#162447;">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
