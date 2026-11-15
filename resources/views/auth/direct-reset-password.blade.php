@extends('layouts.guest')

@section('title', 'Reset Password Langsung - Shanum Elektrik')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        {{-- Back Link --}}
        <div class="mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Login
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="mx-auto w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Reset Password Langsung</h2>
                <p class="text-sm text-gray-500 mt-2">Mode offline — reset password langsung dengan verifikasi email terdaftar.</p>
            </div>

            {{-- Warning --}}
            <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-xs text-amber-700">
                        <strong>Peringatan:</strong> Fitur ini hanya untuk keperluan development/lokal. Di production, gunakan reset via email.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.direct.update') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Terdaftar</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm transition-colors @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email yang terdaftar">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input id="password" type="password" name="password" required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm transition-colors @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm transition-colors"
                        placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 cursor-pointer">
                    Reset Password Sekarang
                </button>
            </form>
        </div>

        {{-- Footer Info --}}
        <div class="mt-6 bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Akun Test yang Tersedia:</h3>
            <div class="space-y-1 text-xs text-gray-500">
                <p>📧 <code class="bg-gray-100 px-1 rounded">admin@toko.com</code> — Admin</p>
                <p>📧 <code class="bg-gray-100 px-1 rounded">staff@toko.com</code> — Staff Barang</p>
                <p>📧 <code class="bg-gray-100 px-1 rounded">kasir@toko.com</code> — Kasir</p>
            </div>
        </div>
    </div>
</div>
@endsection
