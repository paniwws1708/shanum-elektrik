<?php
/**
 * resources/views/inventaris/kategori/index.blade.php
 */
?>
@extends('layouts.app')

@section('title', 'Daftar Kategori — Shanum Elektrik')
@section('page-title', 'Data Kategori')
@section('breadcrumb', 'Inventaris / Kategori')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('inventaris.kategori.create') }}" class="bg-green-600 text-white px-3 py-1 rounded">Tambah Kategori</a>
    </div>

    <table class="min-w-full bg-white border">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse($kategoris as $kategori)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $kategori->nama }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('inventaris.kategori.edit', $kategori) }}" class="text-blue-600 mr-2">Edit</a>
                    <form action="{{ route('inventaris.kategori.destroy', $kategori) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="px-4 py-2 text-center">Tidak ada kategori.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $kategoris->links() }}</div>
@endsection
