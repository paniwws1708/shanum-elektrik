@extends('layouts.guest')

@section('title', 'Login — Shanum Elektrik')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ============================================================
         KOLOM KIRI — Panel Navy (#162447)
    ============================================================ --}}
    <div class="lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 lg:p-16"
         style="background-color:#162447; color:#F7F1E5;">

        {{-- Brand Header --}}
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                 style="background-color:#F7F1E5;">
                <svg class="w-5 h-5" fill="none" stroke="#162447" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-base font-semibold tracking-tight" style="color:#F7F1E5;">Shanum Elektrik</span>
        </div>

        {{-- Hero Copy & Role Info --}}
        <div class="my-12 lg:my-0 max-w-md">
            <h1 class="text-3xl sm:text-4xl font-bold leading-tight tracking-tight" style="color:#F7F1E5;">
                Kelola stok dan kasir toko dalam satu tempat.
            </h1>
            <p class="mt-4 text-sm sm:text-base leading-relaxed" style="color:#c8d8ed;">
                Sistem inventori dan kasir untuk toko alat listrik dan alat tulis kantor.
                Setiap peran melihat menu dan data yang sesuai haknya.
            </p>

            <div class="mt-8 space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4 items-baseline">
                    <span class="font-semibold" style="color:#F7F1E5;">Admin</span>
                    <span class="col-span-2" style="color:#c8d8ed;">Akses ke seluruh fitur dan laporan</span>
                </div>
                <div class="grid grid-cols-3 gap-4 items-baseline">
                    <span class="font-semibold" style="color:#F7F1E5;">Kasir</span>
                    <span class="col-span-2" style="color:#c8d8ed;">Transaksi penjualan dan riwayatnya</span>
                </div>
                <div class="grid grid-cols-3 gap-4 items-baseline">
                    <span class="font-semibold" style="color:#F7F1E5;">Staff Barang</span>
                    <span class="col-span-2" style="color:#c8d8ed;">Stok, barang masuk, rusak, retur, restock</span>
                </div>
            </div>
        </div>

        {{-- Footer Lokasi --}}
        <p class="text-xs" style="color:#8a9bb5;">
            Toko Shanum Elektrik &middot; Ciampel, Karawang
        </p>
    </div>

    {{-- ============================================================
         KOLOM KANAN — Form Login (#F7F1E5)
    ============================================================ --}}
    <div class="lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16"
         style="background-color:#F7F1E5;">
        <div class="w-full max-w-md">

            {{-- Heading --}}
            <div class="mb-7">
                <h2 class="text-2xl font-bold tracking-tight" style="color:#162447;">Masuk ke akun Anda</h2>
                <p class="mt-1.5 text-sm" style="color:#5a7398;">
                    Gunakan email dan kata sandi dari Admin toko.
                </p>
            </div>

            {{-- Flash Success --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm"
                     style="background-color:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#059669;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color:#162447;">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus
                           class="block w-full px-3.5 py-2.5 rounded-xl text-sm transition-colors focus:outline-none"
                           style="border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }};
                                  background-color:{{ $errors->has('email') ? '#fef2f2' : '#ffffff' }};
                                  color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='{{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }}';"
                           placeholder="nama@shanum.test">
                    @error('email')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata sandi dengan toggle lihat/sembunyikan --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color:#162447;">Kata sandi</label>
                    <div class="relative">
                        <input id="password"
                               :type="showPassword ? 'text' : 'password'"
                               name="password"
                               required
                               class="block w-full pl-3.5 pr-11 py-2.5 rounded-xl text-sm transition-colors focus:outline-none"
                               style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                               onfocus="this.style.borderColor='#162447';"
                               onblur="this.style.borderColor='#DDD3BE';"
                               placeholder="Masukkan kata sandi">
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer focus:outline-none"
                                style="color:#8a9bb5;"
                                :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" style="display:none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="h-4 w-4 rounded"
                               style="accent-color:#162447;">
                        <span class="text-sm" style="color:#5a7398;">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-200 cursor-pointer shadow-sm"
                        style="background-color:#162447; color:#F7F1E5;"
                        onmouseover="this.style.backgroundColor='#1e3060';"
                        onmouseout="this.style.backgroundColor='#162447';">
                    Masuk
                </button>
            </form>

            {{-- Catatan Ubah Kata Sandi --}}
            <p class="mt-5 text-xs" style="color:#5a7398;">
                Ubah kata sandi dilakukan setelah masuk, lewat halaman Profil.
            </p>
        </div>
    </div>

</div>
@endsection
