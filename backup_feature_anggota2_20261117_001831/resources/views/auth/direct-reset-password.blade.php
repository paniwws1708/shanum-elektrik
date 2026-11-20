@extends('layouts.guest')

@section('title', 'Reset Password Langsung — Shanum Elektrik')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">

        <div class="mb-6">
            <a href="{{ route('login') }}"
               class="inline-flex items-center text-sm font-medium transition-colors"
               style="color:#162447;"
               onmouseover="this.style.color='#1e3060';"
               onmouseout="this.style.color='#162447';">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Login
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-8" style="border:1px solid #EDE5D4;">
            <div class="text-center mb-6">
                <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                     style="background-color:#fffbeb; border:2px solid #fde68a;">
                    <svg class="w-7 h-7" fill="none" stroke="#b45309" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold" style="color:#162447;">Reset Password Langsung</h2>
                <p class="text-sm mt-2" style="color:#5a7398;">Mode offline — reset tanpa mengirim email.</p>
            </div>

            {{-- Warning --}}
            <div class="mb-5 flex gap-3 p-4 rounded-xl text-sm" style="background-color:#fffbeb; border:1px solid #fde68a;">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" style="color:#d97706;">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p style="color:#92400e;"><strong>Peringatan:</strong> Hanya untuk development/lokal. Di production gunakan reset via email.</p>
            </div>

            <form method="POST" action="{{ route('password.direct.update') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color:#162447;">Email Terdaftar</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="block w-full px-4 py-3 rounded-xl text-sm focus:outline-none transition-colors"
                           style="border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }};
                                  background-color:{{ $errors->has('email') ? '#fef2f2' : '#fafaf9' }};
                                  color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';"
                           placeholder="Masukkan email yang terdaftar">
                    @error('email')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color:#162447;">Password Baru</label>
                    <input id="password" type="password" name="password" required
                           class="block w-full px-4 py-3 rounded-xl text-sm focus:outline-none transition-colors"
                           style="border:1.5px solid #DDD3BE; background-color:#fafaf9; color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium mb-1.5" style="color:#162447;">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="block w-full px-4 py-3 rounded-xl text-sm focus:outline-none transition-colors"
                           style="border:1.5px solid #DDD3BE; background-color:#fafaf9; color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';"
                           placeholder="Ulangi password baru">
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-sm font-semibold transition-all cursor-pointer shadow-sm"
                        style="background-color:#b45309; color:#fffbeb;"
                        onmouseover="this.style.backgroundColor='#92400e';"
                        onmouseout="this.style.backgroundColor='#b45309';">
                    Reset Password Sekarang
                </button>
            </form>
        </div>

        {{-- Akun test info --}}
        <div class="mt-5 bg-white rounded-xl p-4 shadow-sm" style="border:1px solid #EDE5D4;">
            <h3 class="text-xs font-semibold mb-2" style="color:#162447;">Akun Test Tersedia:</h3>
            <div class="space-y-1 text-xs" style="color:#5a7398;">
                <p>📧 <code style="background-color:#F7F1E5; padding:1px 5px; border-radius:4px; color:#162447;">admin@toko.com</code> — Admin</p>
                <p>📧 <code style="background-color:#F7F1E5; padding:1px 5px; border-radius:4px; color:#162447;">staff@toko.com</code> — Staff Barang</p>
                <p>📧 <code style="background-color:#F7F1E5; padding:1px 5px; border-radius:4px; color:#162447;">kasir@toko.com</code> — Kasir</p>
            </div>
        </div>
    </div>
</div>
@endsection
