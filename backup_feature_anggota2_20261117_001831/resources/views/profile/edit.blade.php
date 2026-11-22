@extends('layouts.app')

@section('title', 'Profil Saya — Shanum Elektrik')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'Shanum Elektrik / Profil Saya')

@section('content')
@php
    $user = auth()->user();
    $roleLabels = ['admin'=>'Administrator','staff_barang'=>'Staff Barang','kasir'=>'Kasir'];
    $roleColors = [
        'admin'       => 'bg-red-100 text-red-700 border-red-200',
        'staff_barang'=> 'bg-green-100 text-green-700 border-green-200',
        'kasir'       => 'bg-blue-100 text-blue-700 border-blue-200',
    ];
    $avatarColors = ['admin'=>'bg-red-500','staff_barang'=>'bg-green-500','kasir'=>'bg-blue-500'];
@endphp

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ============================================================
         SECTION 1: INFO PROFIL (Header Card)
    ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Cover --}}
        <div class="h-24 bg-gradient-to-r
            {{ $user->role === 'admin' ? 'from-red-500 to-red-700' : ($user->role === 'staff_barang' ? 'from-green-500 to-green-700' : 'from-blue-500 to-blue-700') }}">
        </div>
        {{-- Avatar + Info --}}
        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-10 mb-4">
                <div class="w-20 h-20 rounded-2xl {{ $avatarColors[$user->role] }} flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="mt-3 sm:mt-0 inline-flex items-center border px-3 py-1 rounded-full text-sm font-semibold {{ $roleColors[$user->role] }}">
                    {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                </span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <p class="text-gray-400 text-xs mt-1">Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
        </div>
    </div>

    {{-- ============================================================
         SECTION 2: EDIT DATA DIRI
    ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Informasi Data Diri</h3>
                    <p class="text-xs text-gray-500">Perbarui nama dan alamat email Anda.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="px-6 py-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Nama --}}
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}"
                        class="block w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                               {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $user->email) }}"
                        class="block w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        placeholder="email@contoh.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role (read-only) --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role Akun</label>
                    <div class="flex items-center px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50">
                        <span class="text-sm text-gray-500">Role Anda adalah</span>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $roleColors[$user->role] }}">
                            {{ $roleLabels[$user->role] }}
                        </span>
                        <span class="ml-auto text-xs text-gray-400">Tidak dapat diubah</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================
         SECTION 3: UBAH PASSWORD
    ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Ubah Password</h3>
                    <p class="text-xs text-gray-500">Pastikan menggunakan password yang kuat dan mudah diingat.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.password') }}" class="px-6 py-5">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Password Saat Ini --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Saat Ini</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="current_password" id="current_password"
                            class="block w-full px-4 py-2.5 pr-11 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                   {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                            placeholder="Masukkan password saat ini">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" id="password"
                            class="block w-full px-4 py-2.5 pr-11 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                            placeholder="Minimal 8 karakter">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                            class="block w-full px-4 py-2.5 pr-11 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                            placeholder="Ulangi password baru">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="mt-4 bg-gray-50 rounded-lg p-3 text-xs text-gray-500 space-y-1">
                <p class="font-medium text-gray-600">Tips password yang kuat:</p>
                <ul class="list-disc list-inside space-y-0.5 ml-1">
                    <li>Minimal 8 karakter</li>
                    <li>Kombinasi huruf besar, kecil, angka, dan simbol</li>
                    <li>Jangan gunakan data pribadi (nama, tanggal lahir)</li>
                </ul>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================
         SECTION 4: INFO AKUN
    ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Informasi Akun</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">ID Pengguna</dt>
                <dd class="text-gray-800 font-semibold">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</dd>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Status Email</dt>
                <dd class="flex items-center gap-2">
                    @if($user->email_verified_at)
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                        <span class="text-green-700 font-medium">Terverifikasi</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        <span class="text-amber-700 font-medium">Belum Verifikasi</span>
                    @endif
                </dd>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tanggal Bergabung</dt>
                <dd class="text-gray-800 font-medium">{{ $user->created_at->format('d F Y, H:i') }}</dd>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Terakhir Diperbarui</dt>
                <dd class="text-gray-800 font-medium">{{ $user->updated_at->format('d F Y, H:i') }}</dd>
            </div>
        </dl>
    </div>

</div>
@endsection
