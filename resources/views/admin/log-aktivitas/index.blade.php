@extends('layouts.app')

@section('title', 'Log Aktivitas — Shanum Elektrik')
@section('page-title', 'Log Aktivitas')
@section('breadcrumb', 'Administrasi / Log Aktivitas')

@section('content')
{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Log Aktivitas</h1>
    <p class="mt-1 text-sm" style="color:#5a7398;">
        Catatan aktivitas pengguna di dalam sistem Shanum Elektrik
    </p>
</div>

@if(!$hasTable)
    <div class="mb-5 px-4 py-3 rounded-xl text-sm" style="background-color:#fef3c7; border:1px solid #fcd34d; color:#92400e;">
        Tabel <code>activity_logs</code> belum tersedia. Jalankan <code>php artisan migrate</code> untuk mulai mencatat log aktivitas.
    </div>
@endif

{{-- Main Card (Filter + Table + Pagination) --}}
<div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
        <div class="lg:col-span-4">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Cari aktivitas</label>
            <input type="text"
                   name="cari"
                   value="{{ request('cari') }}"
                   placeholder="Kata kunci keterangan..."
                   class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                   style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                   onfocus="this.style.borderColor='#162447';"
                   onblur="this.style.borderColor='#DDD3BE';">
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Pengguna</label>
            <select name="user_id"
                    class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                    style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                <option value="">Semua pengguna</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Modul</label>
            <select name="modul"
                    class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                    style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                <option value="">Semua modul</option>
                @foreach($moduls as $m)
                    <option value="{{ $m }}" @selected(request('modul') === $m)>
                        {{ $m }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Dari tanggal</label>
            <input type="date"
                   name="dari_tanggal"
                   value="{{ request('dari_tanggal') }}"
                   class="w-full px-3.5 py-2 rounded-xl text-sm focus:outline-none"
                   style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
        </div>

        <div class="lg:col-span-2 flex items-center gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2 rounded-xl text-sm font-semibold transition-colors cursor-pointer"
                    style="background-color:#EDE5D4; color:#162447;"
                    onmouseover="this.style.backgroundColor='#DDD3BE';"
                    onmouseout="this.style.backgroundColor='#EDE5D4';">
                Terapkan
            </button>

            @if(request()->hasAny(['cari', 'user_id', 'modul', 'dari_tanggal']))
                <a href="{{ route('admin.log-aktivitas.index') }}"
                   class="px-3 py-2 rounded-xl text-sm font-medium transition-colors"
                   style="border:1px solid #DDD3BE; color:#5a7398;"
                   onmouseover="this.style.backgroundColor='#FAF6EE';"
                   onmouseout="this.style.backgroundColor='';">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel Log --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                    <th class="pb-3 pr-3 font-medium">Waktu</th>
                    <th class="pb-3 px-3 font-medium">Pengguna</th>
                    <th class="pb-3 px-3 font-medium">Modul</th>
                    <th class="pb-3 px-3 font-medium text-center">Aksi</th>
                    <th class="pb-3 pl-3 font-medium">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:#F7F1E5;">
                @forelse($logs as $log)
                    @php
                        $aksiStyle = match ($log->aksi) {
                            'create' => 'background-color:#dcfce7; color:#166534;',
                            'update' => 'background-color:#fef3c7; color:#92400e;',
                            'delete' => 'background-color:#fee2e2; color:#991b1b;',
                            default  => 'background-color:#dbeafe; color:#1d4ed8;',
                        };
                        $roleLabel = match ($log->user?->role) {
                            'admin'        => 'Admin',
                            'staff_barang' => 'Staff Barang',
                            'kasir'        => 'Kasir',
                            default        => null,
                        };
                    @endphp
                    <tr class="transition-colors"
                        onmouseover="this.style.backgroundColor='#FAF6EE';"
                        onmouseout="this.style.backgroundColor='';">
                        <td class="whitespace-nowrap py-3.5 pr-3 text-xs" style="color:#5a7398;">
                            {{ $log->created_at->format('d M Y, H.i') }}
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 font-semibold" style="color:#162447;">
                            {{ $log->user->name ?? 'Sistem' }}
                            @if($roleLabel)
                                <span class="text-xs font-normal" style="color:#8a9bb5;">&middot; {{ $roleLabel }}</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  style="background-color:#F7F1E5; color:#162447;">
                                {{ $log->modul }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap py-3.5 px-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase"
                                  style="{{ $aksiStyle }}">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td class="py-3.5 pl-3" style="color:#162447;">
                            {{ $log->deskripsi }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm" style="color:#8a9bb5;">
                            Belum ada log aktivitas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->total() > 0)
        <div class="mt-4 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="border-top:1px solid #EDE5D4;">
            <p class="text-xs" style="color:#8a9bb5;">
                Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
            </p>
            @if($logs->hasPages())
                <div>
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
