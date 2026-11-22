<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shanum Elektrik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased" style="background-color:#F7F1E5;">

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
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        style="display:none;"
    ></div>

    {{-- ============================================================
         SIDEBAR — Navy #162447
    ============================================================ --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:flex"
        style="background-color:#162447;"
    >
        {{-- Logo / Brand --}}
        <div class="flex items-center h-16 px-5 shrink-0" style="border-bottom:1px solid #1e3060;">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 shrink-0" style="background-color:#F7F1E5;">
                <svg class="w-5 h-5" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <span class="text-sm font-bold tracking-tight" style="color:#F7F1E5;">Shanum Elektrik</span>
                <p class="text-xs leading-tight" style="color:#8a9bb5;">Kasir & Inventaris</p>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-4 py-4 shrink-0" style="border-bottom:1px solid #1e3060;">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0"
                     style="background-color:#F7F1E5; color:#162447;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold truncate" style="color:#F7F1E5;">{{ auth()->user()->name }}</p>
                    @php
                        $roleLabels = ['admin'=>'Admin','staff_barang'=>'Staff Barang','kasir'=>'Kasir'];
                        $role = auth()->user()->role;
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold mt-0.5"
                          style="background-color:#F7F1E5; color:#162447;">
                        {{ $roleLabels[$role] ?? ucfirst($role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            {{-- ---- SEMUA ROLE ---- --}}
            <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2 mt-1" style="color:#5a7398;">Umum</p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('*.dashboard') ? 'sidebar-active' : 'sidebar-link' }}"
               @if(request()->routeIs('*.dashboard'))
                   style="background-color:#F7F1E5; color:#162447;"
               @else
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
               @endif>
                <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="{{ request()->routeIs('*.dashboard') ? 'color:#162447;' : 'color:#8a9bb5;' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Profil Saya --}}
            <a href="{{ route('profile.edit') }}"
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
               @if(request()->routeIs('profile.*'))
                   style="background-color:#F7F1E5; color:#162447;"
               @else
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
               @endif>
                <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="{{ request()->routeIs('profile.*') ? 'color:#162447;' : 'color:#8a9bb5;' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Profil Saya
            </a>

            {{-- ---- ADMIN ONLY ---- --}}
            @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-1">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider" style="color:#5a7398;">Administrasi</p>
                </div>

                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';">
                    <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#8a9bb5;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Manajemen Pengguna
                    <span class="ml-auto text-xs px-1.5 py-0.5 rounded font-medium" style="background-color:#1e3060; color:#8a9bb5;">Soon</span>
                </a>
            @endif

{{-- ---- ADMIN & STAFF_BARANG ---- --}}
@if(auth()->user()->isAdmin() || auth()->user()->isStaffBarang())
    <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-semibold uppercase tracking-wider"
           style="color:#5a7398;">
            Inventaris
        </p>
    </div>

    @php
        $inventarisMenu = [
            [
                'label' => 'Data Barang',
                'route' => 'inventaris.barang.index',
                'active' => 'inventaris.barang.*'
            ],
            [
                'label' => 'Kategori',
                'route' => 'inventaris.kategori.index',
                'active' => 'inventaris.kategori.*'
            ],
            [
                'label' => 'Supplier',
                'route' => 'inventaris.supplier.index',
                'active' => 'inventaris.supplier.*'
            ],
            [
                'label' => 'Barang Masuk',
                'route' => 'inventaris.barang_masuk.index',
                'active' => 'inventaris.barang_masuk.*'
            ],

[
    'label' => 'Penyesuaian Stok',
    'route' => 'inventaris.penyesuaian_stok.index',
    'active' => 'inventaris.penyesuaian_stok.*'
],

            [
                'label' => 'Riwayat Stok',
                'route' => 'inventaris.stok_riwayat.index',
                'active' => 'inventaris.stok_riwayat.*'
            ],
        ];
    @endphp

    @foreach($inventarisMenu as $menu)
        @php($isActive = request()->routeIs($menu['active']))

        <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
           class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"

           @if($isActive)
               style="background-color:#F7F1E5; color:#162447;"
           @else
               style="color:#c8d8ed;"
               onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
               onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
           @endif>

            <span class="mr-3 flex h-5 w-5 shrink-0 items-center justify-center">
    @switch($menu['label'])
        @case('Data Barang')
            <i data-lucide="package"></i>
            @break

        @case('Kategori')
            <i data-lucide="tags"></i>
            @break

        @case('Supplier')
            <i data-lucide="truck"></i>
            @break

        @case('Barang Masuk')
            <i data-lucide="package-plus"></i>
            @break
            @case('Penyesuaian Stok')
    <i data-lucide="clipboard-pen-line"></i>
    @break

        @case('Riwayat Stok')
            <i data-lucide="history"></i>
            @break
    @endswitch
</span>

            {{ $menu['label'] }}
        </a>
    @endforeach
@endif

            {{-- ---- ADMIN & KASIR ---- --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
                <div class="pt-4 pb-1">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider" style="color:#5a7398;">Penjualan</p>
                </div>

                @php($isPosActive = request()->routeIs('kasir.pos') || request()->routeIs('kasir.store'))
                <a href="{{ route('kasir.pos') }}"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
                   @if($isPosActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="{{ $isPosActive ? 'color:#162447;' : 'color:#8a9bb5;' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    Transaksi Kasir
                </a>

                @php($isRiwayatActive = request()->routeIs('kasir.riwayat') || request()->routeIs('kasir.show'))
                <a href="{{ route('kasir.riwayat') }}"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
                   @if($isRiwayatActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="{{ $isRiwayatActive ? 'color:#162447;' : 'color:#8a9bb5;' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Riwayat Penjualan
                </a>
            @endif

        </nav>

        {{-- Logout --}}
        <div class="shrink-0 p-4" style="border-top:1px solid #1e3060;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 cursor-pointer"
                        style="color:#c8d8ed;"
                        onmouseover="this.style.backgroundColor='#7f1d1d'; this.style.color='#fecaca';"
                        onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';">
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
        <header class="h-16 flex items-center px-4 lg:px-6 shrink-0 z-10"
                style="background-color:#ffffff; border-bottom:1px solid #EDE5D4;">
            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-md focus:outline-none lg:hidden mr-3 cursor-pointer transition-colors"
                    style="color:#162447;"
                    onmouseover="this.style.backgroundColor='#F7F1E5';"
                    onmouseout="this.style.backgroundColor='';">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <div class="flex-1">
                <h1 class="text-base font-semibold" style="color:#162447;">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <p class="text-xs" style="color:#8a9bb5;">@yield('breadcrumb')</p>
                @endif
            </div>

            {{-- Right side --}}
            <div class="flex items-center space-x-3">
                <div class="hidden md:block text-right">
                    <p class="text-xs" style="color:#8a9bb5;">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-xs font-semibold" style="color:#162447;">{{ now()->format('H:i') }} WIB</p>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-colors"
                   style="border:1px solid #EDE5D4;"
                   onmouseover="this.style.backgroundColor='#F7F1E5';"
                   onmouseout="this.style.backgroundColor='';">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs"
                         style="background-color:#162447; color:#F7F1E5;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold leading-tight" style="color:#162447;">{{ auth()->user()->name }}</p>
                        <p class="text-xs leading-tight" style="color:#8a9bb5;">{{ auth()->user()->email }}</p>
                    </div>
                </a>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-4 lg:mx-6 mt-4 flex items-center gap-3 text-sm px-4 py-3 rounded-xl"
                 style="background-color:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#059669;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 lg:mx-6 mt-4 flex items-center gap-3 text-sm px-4 py-3 rounded-xl"
                 style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#dc2626;">
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
<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
