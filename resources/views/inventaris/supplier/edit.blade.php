@extends('layouts.app')

@section('title', 'Edit Supplier — Shanum Elektrik')
@section('page-title', 'Edit Supplier')
@section('breadcrumb', 'Inventaris / Supplier / Edit')

@section('content')
<div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    <h2 class="mb-1 text-lg font-bold" style="color:#162447;">
        Ubah Data Supplier
    </h2>

    <p class="mb-6 text-sm text-gray-500">
        Perbarui informasi {{ $supplier->nama }}.
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

    <form action="{{ route('inventaris.supplier.update', $supplier) }}"
          method="POST"
          class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Nama Supplier <span class="text-red-500">*</span>
            </label>

            <input
                name="nama"
                value="{{ old('nama', $supplier->nama) }}"
                required
                maxlength="255"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Nomor Telepon
            </label>

            <input
                name="telepon"
                value="{{ old('telepon', $supplier->telepon) }}"
                maxlength="50"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Alamat
            </label>

            <textarea
                name="alamat"
                rows="3"
                maxlength="500"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            >{{ old('alamat', $supplier->alamat) }}</textarea>
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
            <a href="{{ route('inventaris.supplier.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600">
                Batal
            </a>

            <button class="rounded-lg px-4 py-2 text-sm font-semibold text-white"
                    style="background:#162447;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection