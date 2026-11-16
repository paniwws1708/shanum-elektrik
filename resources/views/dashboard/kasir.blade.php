@extends('layouts.app')

@section('title', 'Dashboard Kasir — Shanum Elektrik')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Shanum Elektrik / Dashboard Kasir')

@section('content')
@php
    $user = auth()->user();
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

{{-- Greeting --}}
<div class="mb-6 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-indigo-200 text-sm font-medium">{{ $greeting }},</p>
            <h2 class="text-2xl font-bold mt-0.5">{{ $user->name }} 👋</h2>
            <p class="text-indigo-200 text-sm mt-1">Anda login sebagai
                <span class="bg-white/20 px-2 py-0.5 rounded text-white font-semibold text-xs uppercase tracking-wide">Kasir</span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-4xl font-bold text-white">{{ now()->format('H:i') }}</p>
            <p class="text-indigo-200 text-sm">{{ now()->format('d M Y') }}</p>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Transaksi Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['transaksi_hari_ini'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pendapatan Hari Ini</p>
                <p class="text-3xl font-bold text-green-600 mt-1">Rp 0</p>
                <p class="text-xs text-gray-400 mt-1">Total penjualan hari ini</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item Terjual</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['item_terjual'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Total unit terjual hari ini</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Tombol Transaksi Baru --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center">
    <div class="max-w-sm mx-auto">
        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Mulai Transaksi</h3>
        <p class="text-sm text-gray-500 mb-5">Klik tombol di bawah untuk membuka halaman kasir dan melayani pelanggan.</p>
        <a href="#" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            Buka Kasir
        </a>
    </div>
</div>
@endsection
