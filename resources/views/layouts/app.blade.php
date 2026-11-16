<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shanum Elektrik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

    {{-- ============================================================
         OVERLAY MOBILE
    ============================================================ --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
        style="display:none;"
    ></div>

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:flex"
    >
        {{-- Logo / Brand --}}
        <div class="flex items-center h-16 px-6 border-b border-gray-700 shrink-0">
            <svg class="w-7 h-7 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <div>
                <span class="text-base font-bold tracking-tight text-white">Shanum Elektrik</span>
                <p class="text-xs text-gray-400 leading-tight">Kasir & Inventaris</p>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-4 py-4 border-b border-gray-700 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    @php
                        $roleLabels = ['admin'=>'Admin','staff_barang'=>'Staff Barang','kasir'=>'Kasir'];
                        $roleColors = ['admin'=>'bg-red-500','staff_barang'=>'bg-green-500','kasir'=>'bg-blue-500'];
                        $role = auth()->user()->role;
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $roleColors[$role] ?? 'bg-gray-500' }} text-white mt-0.5">
                        {{ $roleLabels[$role] ?? ucfirst($role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            {{-- ---- SEMUA ROLE ---- --}}
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Umum</p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                       {{ request()->routeIs('*.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 shrink-0 {{ request()->routeIs('*.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Profil Saya --}}
            <a href="{{ route('profile.edit') }}"
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                       {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 shrink-0 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Profil Saya
            </a>

            {{-- ---- ADMIN ONLY ---- --}}
            @if(auth()->user()->isAdmin())
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administrasi</p>
                </div>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="mr-3 h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Manajemen Pengguna
                    <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-700 text-gray-300">Soon</span>
                </a>
            @endif

            {{-- ---- ADMIN & STAFF_BARANG ---- --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isStaffBarang())
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Inventaris</p>
                </div>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="mr-3 h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Data Barang
                    <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-700 text-gray-300">Soon</span>
                </a>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="mr-3 h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stok & Kategori
                    <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-700 text-gray-300">Soon</span>
                </a>
            @endif

            {{-- ---- ADMIN & KASIR ---- --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Penjualan</p>
                </div>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="mr-3 h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    Transaksi Kasir
                    <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-700 text-gray-300">Soon</span>
                </a>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="mr-3 h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Riwayat Penjualan
                    <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-700 text-gray-300">Soon</span>
                </a>
            @endif

        </nav>

        {{-- Logout --}}
        <div class="shrink-0 p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-red-600 hover:text-white transition-colors cursor-pointer">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ============================================================
         MAIN AREA
    ============================================================ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Navbar --}}
        <header class="bg-white border-b border-gray-200 h-16 flex items-center px-4 lg:px-6 shrink-0 z-10">
            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none lg:hidden mr-3 cursor-pointer">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <div class="flex-1">
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <p class="text-xs text-gray-500">@yield('breadcrumb')</p>
                @endif
            </div>

            {{-- Right side: notifications + user --}}
            <div class="flex items-center space-x-3">
                {{-- Date/Time --}}
                <div class="hidden md:block text-right">
                    <p class="text-xs text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-xs font-medium text-gray-700">{{ now()->format('H:i') }} WIB</p>
                </div>

                {{-- Avatar --}}
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-gray-700 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 leading-tight">{{ auth()->user()->email }}</p>
                    </div>
                </a>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-4 lg:mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center gap-3 text-sm">
                <svg class="h-5 w-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 lg:mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 flex items-center gap-3 text-sm">
                <svg class="h-5 w-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

</div>

<script>
    // Simple Alpine.js-like toggle without Alpine dependency
    document.addEventListener('DOMContentLoaded', () => {
        // If Alpine.js not loaded, provide fallback
    });
</script>

</body>
</html>
