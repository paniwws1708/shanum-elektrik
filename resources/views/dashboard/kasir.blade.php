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
                    Kasir
                </span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-4xl font-bold text-white tabular-nums">{{ now()->format('H:i') }}</p>
            <p class="text-sm mt-1" style="color:#8a9bb5;">{{ now()->format('d M Y') }}</p>
        </div>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full opacity-5" style="background-color:#F7F1E5;"></div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#F7F1E5;">
                <svg class="w-6 h-6" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#F7F1E5; color:#162447;">Hari Ini</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['transaksi_hari_ini'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Transaksi Hari Ini</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#ecfdf5;">
                <svg class="w-6 h-6" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#ecfdf5; color:#059669;">Pendapatan</span>
        </div>
        <p class="text-3xl font-bold" style="color:#059669;">Rp 0</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Total Pendapatan</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #EDE5D4;">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color:#eff6ff; color:#3b82f6;">Item</span>
        </div>
        <p class="text-3xl font-bold" style="color:#162447;">{{ $stats['item_terjual'] }}</p>
        <p class="text-xs mt-1" style="color:#8a9bb5;">Item Terjual</p>
    </div>

</div>

{{-- Tombol Transaksi --}}
<div class="bg-white rounded-2xl shadow-sm p-8 text-center" style="border:1px solid #EDE5D4;">
    <div class="max-w-sm mx-auto">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
             style="background-color:#F7F1E5;">
            <svg class="w-8 h-8" fill="none" stroke="#162447" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2" style="color:#162447;">Mulai Transaksi</h3>
        <p class="text-sm mb-6" style="color:#8a9bb5;">Klik tombol di bawah untuk membuka halaman kasir dan melayani pelanggan.</p>
        <a href="#"
           class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-150 shadow-sm"
           style="background-color:#162447; color:#F7F1E5;"
           onmouseover="this.style.backgroundColor='#1e3060';"
           onmouseout="this.style.backgroundColor='#162447';">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            Buka Kasir
        </a>
    </div>
</div>
@endsection
