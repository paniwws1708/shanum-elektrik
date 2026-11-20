@extends('layouts.guest')

@section('title', 'Reset Password — Shanum Elektrik')

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
                     style="background-color:#F7F1E5; border:2px solid #DDD3BE;">
                    <svg class="w-7 h-7" fill="none" stroke="#162447" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold" style="color:#162447;">Reset Password</h2>
                <p class="text-sm mt-2" style="color:#5a7398;">Masukkan password baru Anda.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color:#162447;">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                           class="block w-full px-4 py-3 rounded-xl text-sm focus:outline-none transition-colors"
                           style="border:1.5px solid #DDD3BE; background-color:#fafaf9; color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';"
                           placeholder="nama@email.com">
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
                        style="background-color:#162447; color:#F7F1E5;"
                        onmouseover="this.style.backgroundColor='#1e3060';"
                        onmouseout="this.style.backgroundColor='#162447';">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
