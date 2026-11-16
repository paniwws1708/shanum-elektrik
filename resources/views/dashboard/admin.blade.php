@extends('layouts.app')

@section('title', 'Dashboard Admin — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Admin')

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
                    Administrator
                </span>
            </p>
        </div>
        <div class="hidden md:flex items-center justify-center w-20 h-20 rounded-2xl opacity-10"
             style="background-color:#F7F1E5;">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
    </div>
    {{-- decorative circle --}}
    <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full opacity-5" style="background-color:#F7F1E5;"></div>
    <div class="absolute -right-4 -bottom-10 w-32 h-32 rounded-full opacity-5" style="background-color:#F7F1E5;"></div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    {{-- Total Users --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                 style="background-color:#F7F1E5;">
                <svg class="w-6 h-6" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background-color:#EDE5D4; color:#5a7398;">Pengguna</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['total_users'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Total semua role</p>
        <div class="mt-3 pt-3 flex items-center gap-3 text-xs" style="border-top:1px solid #EDE5D4;">
            <span class="flex items-center gap-1" style="color:#5a7398;">
                <span class="w-2 h-2 rounded-full inline-block" style="background-color:#162447;"></span>
                Admin: {{ $stats['total_admin'] }}
            </span>
            <span class="flex items-center gap-1" style="color:#5a7398;">
                <span class="w-2 h-2 rounded-full inline-block" style="background-color:#1e3060;"></span>
                Staff: {{ $stats['total_staff'] }}
            </span>
            <span class="flex items-center gap-1" style="color:#5a7398;">
                <span class="w-2 h-2 rounded-full inline-block" style="background-color:#8a9bb5;"></span>
                Kasir: {{ $stats['total_kasir'] }}
            </span>
        </div>
    </div>

    {{-- Total Produk --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                 style="background-color:#ecfdf5;">
                <svg class="w-6 h-6" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background-color:#ecfdf5; color:#059669;">Inventaris</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['total_produk'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Item dalam inventaris</p>
        <div class="mt-3 pt-3 text-xs" style="border-top:1px solid #EDE5D4; color:#8a9bb5;">
            Fitur inventaris segera hadir
        </div>
    </div>

    {{-- Transaksi Hari Ini --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                 style="background-color:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background-color:#eff6ff; color:#3b82f6;">Transaksi</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['transaksi_hari_ini'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">{{ now()->format('d M Y') }}</p>
        <div class="mt-3 pt-3 text-xs" style="border-top:1px solid #EDE5D4; color:#8a9bb5;">
            Fitur transaksi segera hadir
        </div>
    </div>

    {{-- Pendapatan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                 style="background-color:#fffbeb;">
                <svg class="w-6 h-6" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background-color:#fffbeb; color:#d97706;">Pendapatan</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">Rp 0</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Total penjualan hari ini</p>
        <div class="mt-3 pt-3 text-xs" style="border-top:1px solid #EDE5D4; color:#8a9bb5;">
            Fitur transaksi segera hadir
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Pengguna Terdaftar --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #EDE5D4;">
            <h3 class="text-sm font-semibold" style="color:#162447;">Pengguna Terdaftar</h3>
            <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                  style="background-color:#F7F1E5; color:#162447;">
                {{ $stats['total_users'] }} akun
            </span>
        </div>
        <div class="divide-y" style="divide-color:#F7F1E5;">
            @forelse($recentUsers as $u)
            @php
                $rBg = ['admin'=>'#162447','staff_barang'=>'#1e3060','kasir'=>'#8a9bb5'];
                $rLabel = ['admin'=>'Admin','staff_barang'=>'Staff','kasir'=>'Kasir'];
                $rTagBg = ['admin'=>'#F7F1E5','staff_barang'=>'#EDE5D4','kasir'=>'#DDD3BE'];
            @endphp
            <div class="flex items-center px-5 py-3 transition-colors"
                 onmouseover="this.style.backgroundColor='#F7F1E5';"
                 onmouseout="this.style.backgroundColor='';">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                     style="background-color:{{ $rBg[$u->role] ?? '#162447' }}; color:#F7F1E5;">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium truncate" style="color:#162447;">{{ $u->name }}</p>
                    <p class="text-xs truncate" style="color:#8a9bb5;">{{ $u->email }}</p>
                </div>
                <span class="ml-2 text-xs px-2 py-0.5 rounded-full font-medium"
                      style="background-color:{{ $rTagBg[$u->role] ?? '#EDE5D4' }}; color:#162447;">
                    {{ $rLabel[$u->role] ?? ucfirst($u->role) }}
                </span>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-sm" style="color:#8a9bb5;">Belum ada pengguna.</div>
            @endforelse
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">
        <div class="px-5 py-4" style="border-bottom:1px solid #EDE5D4;">
            <h3 class="text-sm font-semibold" style="color:#162447;">Akses Cepat</h3>
        </div>
        <div class="p-5 grid grid-cols-2 gap-3">
            @php
            $menus = [
                ['icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z','label'=>'Manajemen User','route'=>'#','bg'=>'#F7F1E5','hover'=>'#EDE5D4'],
                ['icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','label'=>'Data Barang','route'=>'#','bg'=>'#ecfdf5','hover'=>'#d1fae5'],
                ['icon'=>'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z','label'=>'Transaksi','route'=>'#','bg'=>'#eff6ff','hover'=>'#dbeafe'],
                ['icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','label'=>'Laporan','route'=>'#','bg'=>'#fffbeb','hover'=>'#fef3c7'],
            ];
            @endphp
            @foreach($menus as $menu)
            <a href="{{ $menu['route'] }}"
               class="flex flex-col items-center justify-center p-4 rounded-xl transition-all duration-150 text-center"
               style="background-color:{{ $menu['bg'] }};"
               onmouseover="this.style.backgroundColor='{{ $menu['hover'] }}';"
               onmouseout="this.style.backgroundColor='{{ $menu['bg'] }}';">
                <svg class="w-7 h-7 mb-2" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
                </svg>
                <span class="text-xs font-semibold leading-tight" style="color:#162447;">{{ $menu['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
