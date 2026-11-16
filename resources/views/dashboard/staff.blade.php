@extends('layouts.app')

@section('title', 'Dashboard Staff Barang — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Staff Barang')

@section('content')
@php
    $user = auth()->user();
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

{{-- Greeting --}}
<div class="mb-6 bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-6 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-green-200 text-sm font-medium">{{ $greeting }},</p>
            <h2 class="text-2xl font-bold mt-0.5">{{ $user->name }} 👋</h2>
            <p class="text-green-200 text-sm mt-1">Anda login sebagai
                <span class="bg-white/20 px-2 py-0.5 rounded text-white font-semibold text-xs uppercase tracking-wide">Staff Barang</span>
            </p>
        </div>
        <div class="hidden md:block">
            <svg class="w-20 h-20 text-green-400 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Produk</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_produk'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Item dalam inventaris</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Stok Menipis</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['stok_menipis'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Di bawah batas minimum</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Stok Habis</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['stok_habis'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Perlu restock segera</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Aksi Cepat --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">Akses Cepat</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="#" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
            <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center mr-3 shrink-0">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-800">Tambah Barang</p>
                <p class="text-xs text-green-600">Input produk baru</p>
            </div>
        </a>
        <a href="#" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
            <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mr-3 shrink-0">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800">Daftar Barang</p>
                <p class="text-xs text-blue-600">Lihat semua produk</p>
            </div>
        </a>
        <a href="#" class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors group">
            <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center mr-3 shrink-0">
                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-amber-800">Laporan Stok</p>
                <p class="text-xs text-amber-600">Cetak laporan</p>
            </div>
        </a>
    </div>
</div>
@endsection
