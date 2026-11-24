@extends('layouts.app')   {{-- CEK: samakan dengan view teman --}}

@section('content')
<div class="max-w-2xl space-y-4">
    <h1 class="text-xl font-bold">Catat Barang Rusak / Retur</h1>

    {{-- Menampilkan semua pesan error validasi --}}
    @if ($errors->any())
        <div class="p-3 rounded-lg bg-red-100 text-red-800 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- enctype="multipart/form-data" WAJIB kalau form mengunggah file/foto --}}
    <form method="POST" action="{{ route('rusak-retur.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-5 space-y-4">
        @csrf {{-- token keamanan Laravel, wajib di setiap form POST --}}

        {{-- Jenis catatan --}}
        <div>
            <label class="block text-sm font-medium mb-1">Jenis</label>
            {{-- old('tipe') = isi sebelumnya, supaya tidak hilang saat ada error --}}
            <select name="tipe" id="tipe" class="w-full border rounded-lg px-3 py-2">
                <option value="rusak" @selected(old('tipe') === 'rusak')>Barang Rusak</option>
                <option value="retur" @selected(old('tipe') === 'retur')>Retur dari Pembeli</option>
            </select>
        </div>

        {{-- Pilih barang --}}
        <div>
            <label class="block text-sm font-medium mb-1">Barang</label>
            <select name="barang_id" class="w-full border rounded-lg px-3 py-2">
                <option value="">-- Pilih barang --</option>
                @foreach ($barangs as $b)
                    <option value="{{ $b->id }}" @selected(old('barang_id') == $b->id)>
                        {{ $b->kode_barang }} - {{ $b->nama_barang }} (stok: {{ $b->stok }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Jumlah</label>
                <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                {{-- Tanggal otomatis hari ini, max = tidak boleh melebihi hari ini --}}
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                       max="{{ now()->toDateString() }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Bagian ini hanya tampil kalau jenis = retur (diatur JavaScript di bawah) --}}
        <div id="bagian-retur" class="space-y-4 hidden">
            <div>
                <label class="block text-sm font-medium mb-1">Nomor Invoice (opsional)</label>
                <input type="text" name="no_invoice" value="{{ old('no_invoice') }}"
                       placeholder="Isi jika ingin dikaitkan dengan transaksi"
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pembeli (opsional)</label>
                <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="kembali_ke_stok" value="1" @checked(old('kembali_ke_stok'))>
                Barang masih bagus, masukkan kembali ke stok
            </label>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Alasan</label>
            <textarea name="alasan" rows="3"
                      class="w-full border rounded-lg px-3 py-2">{{ old('alasan') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Foto Bukti (JPG/PNG, maks 2 MB)</label>
            <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png" class="w-full text-sm">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm">Simpan</button>
            <a href="{{ route('rusak-retur.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>

{{-- JavaScript kecil: tampilkan bagian retur hanya saat jenis = retur --}}
<script>
    const tipe = document.getElementById('tipe');
    const bagianRetur = document.getElementById('bagian-retur');

    function aturTampilan() {
        // classList.toggle('hidden', kondisi) = sembunyikan kalau kondisi true
        bagianRetur.classList.toggle('hidden', tipe.value !== 'retur');
    }

    tipe.addEventListener('change', aturTampilan); // jalan saat pilihan berubah
    aturTampilan();                                // jalan sekali saat halaman dibuka
</script>
@endsection