@extends('layouts.app')

@section('title', 'Pemasukan — Shanum Elektrik')
@section('page-title', 'Keuangan')
@section('breadcrumb', 'Administrasi / Keuangan / Pemasukan')

@section('content')
<div class="space-y-4">
    @include('keuangan._header')

    {{-- Form tambah pemasukan (sumber lain selain penjualan) --}}
    <form method="POST" action="{{ route('keuangan.pemasukan.store') }}"
          class="bg-white rounded-xl shadow p-5 grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
        @csrf {{-- token keamanan, wajib di setiap form POST --}}
        <div>
            <label class="block text-xs mb-1">Tanggal</label>
            {{-- old('tanggal', ...) = isi sebelumnya kalau ada error, kalau tidak: hari ini --}}
            <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                   max="{{ now()->toDateString() }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs mb-1">Jumlah (Rp)</label>
            <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs mb-1">Keterangan</label>
            <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                   placeholder="contoh: Jasa pasang instalasi"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm">+ Catat Pemasukan</button>
    </form>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap items-end gap-2">
        <select name="sumber" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">Semua sumber</option>
            @foreach (\App\Models\Pemasukan::SUMBER as $kode => $label)
                <option value="{{ $kode }}" @selected(request('sumber') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="dari" value="{{ request('dari') }}" class="border rounded-lg px-3 py-2 text-sm">
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="border rounded-lg px-3 py-2 text-sm">
        <button class="px-4 py-2 bg-slate-200 rounded-lg text-sm">Filter</button>
    </form>

    <div class="text-sm">Total sesuai filter:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Sumber</th>
                    <th class="p-3">Keterangan</th>
                    <th class="p-3 text-right">Jumlah</th>
                    <th class="p-3">Dicatat oleh</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $item->tanggal->format('d M Y') }}</td>
                        <td class="p-3">
                            {{-- Badge: biru untuk penjualan, abu-abu untuk lainnya --}}
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $item->sumber === 'penjualan_harian' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ \App\Models\Pemasukan::SUMBER[$item->sumber] ?? $item->sumber }}
                            </span>
                        </td>
                        <td class="p-3">{{ $item->keterangan }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="p-3">{{ $item->user?->name }}</td>
                        <td class="p-3">
                            {{-- Form hapus dengan konfirmasi sebelum dikirim --}}
                            <form method="POST" action="{{ route('keuangan.pemasukan.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus pemasukan ini?')">
                                @csrf
                                @method('DELETE') {{-- HTML hanya punya GET/POST, jadi DELETE "dipalsukan" --}}
                                <button class="text-red-600 underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-slate-500">Belum ada pemasukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $data->links() }}
</div>
@endsection