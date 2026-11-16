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

{{-- Greeting --}}
<div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-blue-200 text-sm font-medium">{{ $greeting }},</p>
            <h2 class="text-2xl font-bold mt-0.5">{{ $user->name }} 👋</h2>
            <p class="text-blue-200 text-sm mt-1">Anda login sebagai
                <span class="bg-white/20 px-2 py-0.5 rounded text-white font-semibold text-xs uppercase tracking-wide">Admin</span>
            </p>
        </div>
        <div class="hidden md:block">
            <svg class="w-20 h-20 text-blue-400 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    {{-- Total Users --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Semua role aktif</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>Admin: {{ $stats['total_admin'] }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>Staff: {{ $stats['total_staff'] }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>Kasir: {{ $stats['total_kasir'] }}</span>
        </div>
    </div>

    {{-- Total Produk --}}
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
        <div class="mt-4">
            <span class="text-xs text-gray-400">Fitur inventaris segera hadir</span>
        </div>
    </div>

    {{-- Transaksi Hari Ini --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Transaksi Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['transaksi_hari_ini'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-xs text-gray-400">Fitur transaksi segera hadir</span>
        </div>
    </div>

    {{-- Pendapatan Hari Ini --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pendapatan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">Rp 0</p>
                <p class="text-xs text-gray-400 mt-1">Total penjualan</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-xs text-gray-400">Fitur transaksi segera hadir</span>
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Pengguna Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">Pengguna Terdaftar</h3>
                <span class="text-xs text-gray-400">{{ $stats['total_users'] }} total</span>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $u)
            <div class="flex items-center px-5 py-3 hover:bg-gray-50 transition-colors">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0
                    {{ $u->role === 'admin' ? 'bg-red-500' : ($u->role === 'staff_barang' ? 'bg-green-500' : 'bg-blue-500') }}">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $u->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                </div>
                <span class="ml-2 text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $u->role === 'admin' ? 'bg-red-100 text-red-700' : ($u->role === 'staff_barang' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                    {{ $u->role === 'staff_barang' ? 'Staff' : ucfirst($u->role) }}
                </span>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-sm text-gray-400">Belum ada pengguna.</div>
            @endforelse
        </div>
    </div>

    {{-- Menu Cepat Admin --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Akses Cepat</h3>
        </div>
        <div class="p-5 grid grid-cols-2 gap-3">
            @php
            $menus = [
                ['icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z', 'label'=>'Manajemen User', 'color'=>'blue', 'route'=>'#'],
                ['icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label'=>'Data Barang', 'color'=>'green', 'route'=>'#'],
                ['icon'=>'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z', 'label'=>'Transaksi', 'color'=>'purple', 'route'=>'#'],
                ['icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label'=>'Laporan', 'color'=>'amber', 'route'=>'#'],
            ];
            $colors = ['blue'=>'bg-blue-50 hover:bg-blue-100 text-blue-700','green'=>'bg-green-50 hover:bg-green-100 text-green-700','purple'=>'bg-purple-50 hover:bg-purple-100 text-purple-700','amber'=>'bg-amber-50 hover:bg-amber-100 text-amber-700'];
            @endphp
            @foreach($menus as $menu)
            <a href="{{ $menu['route'] }}"
               class="flex flex-col items-center justify-center p-4 rounded-xl transition-colors {{ $colors[$menu['color']] }}">
                <svg class="w-7 h-7 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ $menu['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
