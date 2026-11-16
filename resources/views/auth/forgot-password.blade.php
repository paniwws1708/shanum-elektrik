@extends('layouts.guest')

@section('title', 'Lupa Password — Shanum Elektrik')

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold" style="color:#162447;">Reset Password via Email</h2>
                <p class="text-sm mt-2" style="color:#5a7398;">Masukkan email Anda, kami akan kirim link reset password.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm"
                     style="background-color:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#059669;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color:#162447;">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="block w-full px-4 py-3 rounded-xl text-sm focus:outline-none transition-colors"
                           style="border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#DDD3BE' }};
                                  background-color:{{ $errors->has('email') ? '#fef2f2' : '#fafaf9' }};
                                  color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';"
                           placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-sm font-semibold transition-all cursor-pointer shadow-sm"
                        style="background-color:#162447; color:#F7F1E5;"
                        onmouseover="this.style.backgroundColor='#1e3060';"
                        onmouseout="this.style.backgroundColor='#162447';">
                    Kirim Link Reset Password
                </button>
            </form>

            <div class="mt-5 p-4 rounded-xl text-xs" style="background-color:#F7F1E5; color:#5a7398;">
                <strong style="color:#162447;">Info:</strong> Gunakan Mailtrap untuk development.
                Atur <code style="background-color:#EDE5D4; padding:1px 4px; border-radius:4px;">MAIL_MAILER</code> di <code style="background-color:#EDE5D4; padding:1px 4px; border-radius:4px;">.env</code>.
            </div>
        </div>
    </div>
</div>
@endsection
