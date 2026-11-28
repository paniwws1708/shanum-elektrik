<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shanum Elektrik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased" style="background-color:#F7F1E5; color:#162447;">

@php
    $roleLabels = ['admin' => 'Admin', 'staff_barang' => 'Staff Barang', 'kasir' => 'Kasir'];
    $role = auth()->user()->role;
@endphp

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
        class="fixed inset-y-0 left-0 z-30 w-60 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:flex shrink-0"
        style="background-color:#162447;"
    >
        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-5 pt-6 pb-5 shrink-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background-color:#F7F1E5;">
                <svg class="w-5 h-5" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <span class="block text-sm font-bold tracking-tight leading-tight" style="color:#F7F1E5;">Shanum Elektrik</span>
                <p class="text-xs leading-tight mt-0.5" style="color:#8a9bb5;">Panel {{ $roleLabels[$role] ?? ucfirst($role) }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('*.dashboard') ? 'sidebar-active font-semibold' : 'sidebar-link' }}"
               @if(request()->routeIs('*.dashboard'))
                   style="background-color:#F7F1E5; color:#162447;"
               @else
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
               @endif>
                <i data-lucide="home" class="mr-3 h-4 w-4 shrink-0" style="{{ request()->routeIs('*.dashboard') ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                Dashboard
            </a>

            {{-- ---- ADMIN ONLY: Pengguna ---- --}}
            @if(auth()->user()->isAdmin())
                @php
                    $isPenggunaActive = request()->routeIs('profile.*');
                @endphp
                <a href="{{ route('profile.edit') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isPenggunaActive ? 'font-semibold' : '' }}"
                   @if($isPenggunaActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="bar-chart-3" class="mr-3 h-5 w-5 shrink-0"></i>
                    Laporan Toko
                </a>
                <a href="{{ route('keuangan.index') }}"
                class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
                @if(request()->routeIs('keuangan.*'))
                    {{-- Menu aktif: latar krem, tulisan biru tua --}}
                    style="background-color:#F7F1E5; color:#162447;"
                @else
                    {{-- Menu biasa: berubah warna saat kursor diarahkan --}}
                    style="color:#c8d8ed;"
                    onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                @endif>
                    {{-- Ikon dompet dari Lucide (library ikon yang sudah dipakai di sidebarmu) --}}
                    <i data-lucide="wallet" class="mr-3 h-5 w-5 shrink-0"></i>
                    Keuangan
                </a>

                <a hre  f="#"
                   class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150"
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';">
                    <svg class="mr-3 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#8a9bb5;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Manajemen Pengguna
                    <span class="ml-auto text-xs px-1.5 py-0.5 rounded font-medium" style="background-color:#1e3060; color:#8a9bb5;">Soon</span>
                    <i data-lucide="users" class="mr-3 h-4 w-4 shrink-0" style="{{ $isPenggunaActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Pengguna
                </a>
            @endif

            {{-- ---- ADMIN & STAFF_BARANG ---- --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isStaffBarang())
                @php
                    $inventarisMenu = [
                        [
                            'label' => 'Data Barang',
                            'route' => 'inventaris.barang.index',
                            'active' => 'inventaris.barang.*',
                            'icon' => 'package',
                        ],
                        [
                            'label' => 'Kategori',
                            'route' => 'inventaris.kategori.index',
                            'active' => 'inventaris.kategori.*',
                            'icon' => 'tag',
                        ],
                        [
                            'label' => 'Monitoring Stok',
                            'route' => 'inventaris.monitoring_stok.index',
                            'active' => 'inventaris.monitoring_stok.*',
                            'icon' => 'bar-chart-2',
                        ],
                    ];
                @endphp

                @foreach($inventarisMenu as $menu)
                    @php
                        $isActive = request()->routeIs($menu['active']);
                    @endphp
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                       class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isActive ? 'font-semibold' : '' }}"
                       @if($isActive)
                           style="background-color:#F7F1E5; color:#162447;"
                       @else
                           style="color:#c8d8ed;"
                           onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                           onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                       @endif>
                        <i data-lucide="{{ $menu['icon'] }}" class="mr-3 h-4 w-4 shrink-0" style="{{ $isActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                        {{ $menu['label'] }}
                    </a>
                @endforeach

                {{-- Pengajuan Restock (belum ada route) --}}
                <a href="#"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
                   style="color:#c8d8ed;"
                   onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';">
                    <i data-lucide="clipboard-list" class="mr-3 h-4 w-4 shrink-0" style="color:#8a9bb5;"></i>
                    Pengajuan Restock
                </a>
            @endif

            {{-- ---- ADMIN ONLY: Transaksi & Keuangan ---- --}}
            @if(auth()->user()->isAdmin())
                @php
                    $isAdminTransaksiActive = request()->routeIs('kasir.riwayat') || request()->routeIs('kasir.show');
                @endphp
                <a href="{{ Route::has('kasir.riwayat') ? route('kasir.riwayat') : '#' }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isAdminTransaksiActive ? 'font-semibold' : '' }}"
                   @if($isAdminTransaksiActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="shopping-cart" class="mr-3 h-4 w-4 shrink-0" style="{{ $isAdminTransaksiActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Transaksi
                </a>

                @php
                    $isKeuanganActive = request()->routeIs('keuangan.*');
                @endphp
                <a href="{{ route('keuangan.index') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isKeuanganActive ? 'font-semibold' : '' }}"
                   @if($isKeuanganActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="wallet" class="mr-3 h-4 w-4 shrink-0" style="{{ $isKeuanganActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Keuangan
                </a>
            @endif

            {{-- ---- KASIR ONLY: Transaksi Baru & Riwayat Transaksi ---- --}}
            @if(auth()->user()->isKasir())
                @php
                    $isPosActive = request()->routeIs('kasir.pos') || request()->routeIs('kasir.store');
                @endphp
                <a href="{{ route('kasir.pos') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isPosActive ? 'font-semibold' : '' }}"
                   @if($isPosActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="shopping-cart" class="mr-3 h-4 w-4 shrink-0" style="{{ $isPosActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Transaksi Baru
                </a>

                @php
                    $isRiwayatActive = request()->routeIs('kasir.riwayat') || request()->routeIs('kasir.show');
                @endphp
                <a href="{{ route('kasir.riwayat') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isRiwayatActive ? 'font-semibold' : '' }}"
                   @if($isRiwayatActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="receipt" class="mr-3 h-4 w-4 shrink-0" style="{{ $isRiwayatActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Riwayat Transaksi
                </a>
            @endif

            {{-- ---- ADMIN & STAFF_BARANG: Rusak dan Retur ---- --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isStaffBarang())
                @php
                    $isRusakActive = request()->routeIs('rusak-retur.*');
                @endphp
                <a href="{{ Route::has('rusak-retur.index') ? route('rusak-retur.index') : '#' }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isRusakActive ? 'font-semibold' : '' }}"
                   @if($isRusakActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="alert-triangle" class="mr-3 h-4 w-4 shrink-0" style="{{ $isRusakActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Rusak dan Retur
                </a>
            @endif

            {{-- ---- ADMIN ONLY: Laporan & Log Aktivitas ---- --}}
            @if(auth()->user()->isAdmin())
                @php
                    $isLaporanActive = request()->routeIs('admin.laporan.*');
                @endphp
                <a href="{{ route('admin.laporan.index') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isLaporanActive ? 'font-semibold' : '' }}"
                   @if($isLaporanActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="file-text" class="mr-3 h-4 w-4 shrink-0" style="{{ $isLaporanActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Laporan
                </a>

                @php
                    $isLogActive = request()->routeIs('admin.log-aktivitas.*');
                @endphp
                <a href="{{ route('admin.log-aktivitas.index') }}"
                   class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isLogActive ? 'font-semibold' : '' }}"
                   @if($isLogActive)
                       style="background-color:#F7F1E5; color:#162447;"
                   @else
                       style="color:#c8d8ed;"
                       onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#c8d8ed';"
                   @endif>
                    <i data-lucide="activity" class="mr-3 h-4 w-4 shrink-0" style="{{ $isLogActive ? 'color:#162447;' : 'color:#8a9bb5;' }}"></i>
                    Log Aktivitas
                </a>
            @endif

        </nav>

        {{-- User Profile & Logout (Bottom Sidebar) --}}
        <div class="shrink-0 px-4 py-4 flex items-center justify-between gap-2" style="border-top:1px solid #1e3060;">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 min-w-0 flex-1 group">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0"
                     style="background-color:#F7F1E5; color:#162447;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold truncate group-hover:underline" style="color:#F7F1E5;">{{ auth()->user()->name }}</p>
                    <p class="text-xs leading-tight" style="color:#8a9bb5;">Profil</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        title="Keluar"
                        class="p-2 rounded-lg transition-colors cursor-pointer"
                        style="color:#8a9bb5;"
                        onmouseover="this.style.backgroundColor='#1e3060'; this.style.color='#F7F1E5';"
                        onmouseout="this.style.backgroundColor=''; this.style.color='#8a9bb5';">
                    <i data-lucide="log-out" class="h-4 w-4"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- ============================================================
         MAIN AREA
    ============================================================ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Mobile Header Trigger (hanya tampil di layar kecil) --}}
        <div class="lg:hidden flex items-center justify-between px-4 py-3 shrink-0"
             style="background-color:#162447; color:#F7F1E5;">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg focus:outline-none cursor-pointer transition-colors"
                    style="color:#F7F1E5;">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-sm font-bold">Shanum Elektrik</span>
            <div class="w-8"></div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 lg:mx-8 mt-5 flex items-center gap-3 text-sm px-4 py-3 rounded-xl"
                 style="background-color:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#059669;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 lg:mx-8 mt-5 flex items-center gap-3 text-sm px-4 py-3 rounded-xl"
                 style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#dc2626;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
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
