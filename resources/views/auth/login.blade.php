@extends('layouts.guest')

@section('title', 'Login — Shanum Elektrik')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">

        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-md"
                 style="background-color:#162447;">
                <svg class="w-8 h-8" fill="none" stroke="#F7F1E5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold" style="color:#162447;">Shanum Elektrik</h1>
            <p class="mt-1 text-sm" style="color:#5a7398;">Sistem Kasir & Inventaris</p>
        </div>

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm"
                 style="background-color:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#059669;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-8" style="border:1px solid #EDE5D4;">
            <h2 class="text-lg font-semibold mb-6" style="color:#162447;">Masuk ke Akun Anda</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color:#162447;">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:#8a9bb5;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autofocus
                               class="block w-full pl-10 pr-3 py-3 rounded-xl text-sm transition-colors focus:outline-none"
                               style="border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }};
                                      background-color:{{ $errors->has('email') ? '#fef2f2' : '#fafaf9' }};
                                      color:#162447;"
                               onfocus="this.style.borderColor='#162447'; this.style.backgroundColor='#fff';"
                               onblur="this.style.borderColor='{{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }}'; this.style.backgroundColor='{{ $errors->has('email') ? '#fef2f2' : '#fafaf9' }}';"
                               placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color:#162447;">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:#8a9bb5;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                               class="block w-full pl-10 pr-3 py-3 rounded-xl text-sm transition-colors focus:outline-none"
                               style="border:1.5px solid #DDD3BE; background-color:#fafaf9; color:#162447;"
                               onfocus="this.style.borderColor='#162447'; this.style.backgroundColor='#fff';"
                               onblur="this.style.borderColor='#DDD3BE'; this.style.backgroundColor='#fafaf9';"
                               placeholder="Masukkan password">
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

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full" style="border-top:1px solid #EDE5D4;"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-sm" style="color:#8a9bb5;">Lupa password?</span>
                </div>
            </div>

            {{-- Forgot Password Links --}}
            <div class="space-y-3">
                <a href="{{ route('password.request') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
                   style="border:1.5px solid #DDD3BE; color:#162447; background-color:#F7F1E5;"
                   onmouseover="this.style.backgroundColor='#EDE5D4';"
                   onmouseout="this.style.backgroundColor='#F7F1E5';">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Reset via Email
                </a>
                <a href="{{ route('password.direct') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
                   style="border:1.5px solid #c8b89a; color:#7a5c1e; background-color:#fffbeb;"
                   onmouseover="this.style.backgroundColor='#fef3c7';"
                   onmouseout="this.style.backgroundColor='#fffbeb';">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Reset Langsung (Offline)
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs mt-6" style="color:#8a9bb5;">
            &copy; {{ date('Y') }} Shanum Elektrik. All rights reserved.
        </p>
    </div>
</div>
@endsection
