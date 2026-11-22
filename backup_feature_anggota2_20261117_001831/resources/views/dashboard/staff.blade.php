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

{{-- Greeting Banner --}}
<div class="mb-6 rounded-2xl p-6 shadow-sm text-white overflow-hidden relative"
     style="background: linear-gradient(135deg, #162447 0%, #0d1a35 100%);">
    <div class="relative z-10 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium mb-1" style="color:#c8d8ed;">{{ $greeting }},</p>
            <h2 class="text-2xl font-bold text-white">{{ $user->name }} 👋</h2>
            <p class="text-sm mt-2" style="color:#8a9bb5;">
                Login sebagai
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ml-1"
                      style="background-color:#F7F1E5; color:#162447;">
                    Staff Barang
                </span>
            </p>
        </div>
        <div class="hidden md:flex items-center justify-center w-20 h-20 rounded-2xl opacity-10"
             style="background-color:#F7F1E5;">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full opacity-5" style="background-color:#F7F1E5;"></div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#ecfdf5;">
                <svg class="w-6 h-6" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#ecfdf5; color:#059669;">Total</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['total_produk'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Total Produk</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#fffbeb;">
                <svg class="w-6 h-6" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#fffbeb; color:#d97706;">Warning</span>
        </div>
        <p class="text-3xl font-bold" style="color:#d97706;">{{ $stats['stok_menipis'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Stok Menipis</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#fef2f2;">
                <svg class="w-6 h-6" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#fef2f2; color:#dc2626;">Habis</span>
        </div>
        <p class="text-3xl font-bold" style="color:#dc2626;">{{ $stats['stok_habis'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Stok Habis</p>
    </div>

</div>

{{-- Aksi Cepat --}}
<div class="bg-white rounded-2xl shadow-sm" style="border:1px solid #EDE5D4;">
    <div class="px-5 py-4" style="border-bottom:1px solid #EDE5D4;">
        <h3 class="text-sm font-semibold" style="color:#162447;">Akses Cepat</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
        $menus = [
            ['icon'=>'M12 6v6m0 0v6m0-6h6m-6 0H6','label'=>'Tambah Barang','desc'=>'Input produk baru','bg'=>'#F7F1E5','hover'=>'#EDE5D4'],
            ['icon'=>'M4 6h16M4 10h16M4 14h16M4 18h16','label'=>'Daftar Barang','desc'=>'Lihat semua produk','bg'=>'#ecfdf5','hover'=>'#d1fae5'],
            ['icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','label'=>'Laporan Stok','desc'=>'Cetak laporan','bg'=>'#fffbeb','hover'=>'#fef3c7'],
        ];
        @endphp
        @foreach($menus as $menu)
        <a href="#"
           class="flex items-center p-4 rounded-xl transition-all duration-150"
           style="background-color:{{ $menu['bg'] }};"
           onmouseover="this.style.backgroundColor='{{ $menu['hover'] }}';"
           onmouseout="this.style.backgroundColor='{{ $menu['bg'] }}';">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 shrink-0"
                 style="background-color:rgba(22,36,71,0.1);">
                <svg class="w-5 h-5" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold" style="color:#162447;">{{ $menu['label'] }}</p>
                <p class="text-xs" style="color:#8a9bb5;">{{ $menu['desc'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
