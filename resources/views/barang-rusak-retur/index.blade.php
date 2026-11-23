{{--
  CEK: baris @extends dan @section di bawah harus SAMA dengan view teman.
  Buka misalnya resources/views/barang-masuk/index.blade.php, lihat 2-3 baris pertama,
  lalu samakan namanya (bisa jadi 'layouts.app' atau 'layouts.main', dll).
  Kelas CSS di bawah memakai Tailwind. Kalau project kamu tidak pakai Tailwind,
  samakan dengan gaya halaman teman.
--}}
@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- Judul halaman + tombol tambah --}}
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">Barang Rusak &amp; Retur</h1>
        <a href="{{ route('rusak-retur.create') }}"
           class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm">+ Catat Baru</a>
    </div>

    {{-- Pesan sukses setelah menyimpan --}}
    @if (session('success'))
        <div class="p-3 rounded-lg bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Form filter & pencarian (dikirim lewat URL, method GET) --}}
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ request('cari') }}"
               placeholder="Cari nama / kode barang"
               class="border rounded-lg px-3 py-2 text-sm">
        <select name="tipe" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">Semua jenis</option>
            <option value="rusak" @selected(request('tipe') === 'rusak')>Rusak</option>
            <option value="retur" @selected(request('tipe') === 'retur')>Retur</option>
        </select>
        <button class="px-4 py-2 bg-slate-200 rounded-lg text-sm">Filter</button>
    </form>

    {{-- Tabel data --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Barang</th>
                    <th class="p-3">Jumlah</th>
                    <th class="p-3">Alasan</th>
                    <th class="p-3">Dicatat oleh</th>
                    <th class="p-3">Bukti</th>
                </tr>
            </thead>
            <tbody>
                {{-- @forelse = perulangan; kalau data kosong, jalankan @empty --}}
                @forelse ($data as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $item->tanggal->format('d M Y') }}</td>
                        <td class="p-3">
                            {{-- Badge warna: merah untuk rusak, biru untuk retur --}}
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $item->tipe === 'rusak' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($item->tipe) }}
                            </span>
                        </td>
                        <td class="p-3">
                            {{-- ?-> = aman walaupun barang sudah dihapus (tidak error) --}}
                            {{ $item->barang?->nama_barang ?? '-' }}
                            <div class="text-xs text-slate-500">{{ $item->barang?->kode_barang }}</div>
                        </td>
                        <td class="p-3">
                            {{ $item->jumlah }}
                            @if ($item->tipe === 'retur')
                                <div class="text-xs text-slate-500">
                                    {{ $item->kembali_ke_stok ? 'Masuk stok lagi' : 'Tidak masuk stok' }}
                                </div>
                            @endif
                        </td>
                        <td class="p-3">
                            {{ $item->alasan }}
                            @if ($item->nama_pembeli)
                                <div class="text-xs text-slate-500">Pembeli: {{ $item->nama_pembeli }}</div>
                            @endif
                        </td>
                        <td class="p-3">{{ $item->user?->name }}</td>
                        <td class="p-3">
                            {{-- Foto dibuka di tab baru. Butuh "php artisan storage:link" --}}
                            <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank"
                               class="text-blue-600 underline">Lihat foto</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-500">Belum ada catatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tombol halaman 1, 2, 3... --}}
    {{ $data->links() }}
</div>
@endsection