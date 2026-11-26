{{-- Daftar tab: nama route => tulisan --}}
@php
    $tabs = [
        'keuangan.index'                  => 'Ringkasan',
        'keuangan.pemasukan.index'        => 'Pemasukan',
        'keuangan.pengeluaran.index'      => 'Pengeluaran',
        'keuangan.penjualan-harian.index' => 'Penjualan Harian',
    ];
@endphp

{{-- Tombol tab. Tab yang sedang dibuka warnanya gelap. --}}
<div class="flex flex-wrap gap-2">
    @foreach ($tabs as $route => $label)
        <a href="{{ route($route) }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs($route) ? 'bg-slate-900 text-white' : 'bg-white shadow' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Pesan sukses --}}
@if (session('success'))
    <div class="p-3 rounded-lg bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
@endif

{{-- Pesan error validasi --}}
@if ($errors->any())
    <div class="p-3 rounded-lg bg-red-100 text-red-800 text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif