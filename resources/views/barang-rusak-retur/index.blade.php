@extends('layouts.app')

@section('title', 'Barang Rusak dan Retur — Shanum Elektrik')
@section('page-title', 'Barang Rusak dan Retur')
@section('breadcrumb', 'Inventaris / Barang Rusak & Retur')

@section('content')
{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Barang Rusak dan Retur</h1>
    <p class="mt-1 text-sm" style="color:#5a7398;">
        Catat barang rusak atau barang yang dikembalikan pembeli, lengkap dengan foto bukti
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

    {{-- ============================================================
         KOLOM KIRI — Catat baru (5/12)
    ============================================================ --}}
    <div class="lg:col-span-5 bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;"
         x-data="{
             tipe: '{{ old('tipe', 'rusak') }}',
             fileName: '',
             fileInfo: '',
             fileValid: false,
             handleFile(e) {
                 const f = e.target.files[0];
                 if (!f) { this.fileName = ''; this.fileInfo = ''; this.fileValid = false; return; }
                 this.fileName = f.name;
                 const ext = f.name.split('.').pop().toUpperCase();
                 const mb = (f.size / (1024 * 1024)).toFixed(1).replace('.', ',');
                 this.fileValid = f.size <= 2 * 1024 * 1024;
                 this.fileInfo = (this.fileValid ? 'Valid' : 'Melebihi 2 MB') + ' · ' + ext + ' · ' + mb + ' MB';
             }
         }">
        <h2 class="text-lg font-bold mb-4" style="color:#162447;">Catat baru</h2>

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-xs" style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('rusak-retur.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Jenis pencatatan --}}
            <div>
                <label class="block text-xs font-medium mb-2" style="color:#5a7398;">Jenis pencatatan</label>
                <div class="grid grid-cols-2 gap-2.5">
                    <label class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-medium cursor-pointer transition-colors"
                           :style="tipe === 'rusak' ? 'border:1.5px solid #162447; background-color:#FAF6EE; color:#162447;' : 'border:1.5px solid #DDD3BE; background-color:#ffffff; color:#5a7398;'">
                        <input type="radio" name="tipe" value="rusak" x-model="tipe" style="accent-color:#162447;">
                        <span>Barang rusak</span>
                    </label>
                    <label class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-medium cursor-pointer transition-colors"
                           :style="tipe === 'retur' ? 'border:1.5px solid #162447; background-color:#FAF6EE; color:#162447;' : 'border:1.5px solid #DDD3BE; background-color:#ffffff; color:#5a7398;'">
                        <input type="radio" name="tipe" value="retur" x-model="tipe" style="accent-color:#162447;">
                        <span>Retur pembeli</span>
                    </label>
                </div>
            </div>

            {{-- Pilih barang --}}
            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Barang</label>
                <select name="barang_id"
                        class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                        style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                    <option value="">-- Pilih barang --</option>
                    @foreach ($barangs as $b)
                        <option value="{{ $b->id }}" @selected(old('barang_id') == $b->id)>
                            {{ $b->kode_barang ? $b->kode_barang . ' · ' : '' }}{{ $b->nama_barang }} (stok: {{ $b->stok }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah & Tanggal --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Jumlah</label>
                    <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}" placeholder="1"
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                           style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                           style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                </div>
            </div>

            {{-- Bagian khusus Retur --}}
            <div x-show="tipe === 'retur'" style="display:none;" class="space-y-3 p-3.5 rounded-xl"
                 style="background-color:#FAF6EE; border:1px solid #EDE5D4;">
                <div>
                    <label class="block text-xs font-medium mb-1" style="color:#5a7398;">Nomor Invoice (opsional)</label>
                    <input type="text" name="no_invoice" value="{{ old('no_invoice') }}"
                           placeholder="Isi jika ingin dikaitkan dengan transaksi"
                           class="w-full px-3 py-2 rounded-xl text-sm focus:outline-none"
                           style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1" style="color:#5a7398;">Nama Pembeli (opsional)</label>
                    <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}"
                           class="w-full px-3 py-2 rounded-xl text-sm focus:outline-none"
                           style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                </div>
                <label class="flex items-center gap-2 text-xs font-medium cursor-pointer" style="color:#162447;">
                    <input type="checkbox" name="kembali_ke_stok" value="1" @checked(old('kembali_ke_stok')) style="accent-color:#162447;">
                    Barang masih bagus, masukkan kembali ke stok
                </label>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Keterangan</label>
                <textarea name="alasan" rows="3"
                          placeholder="Contoh: Bohlam pecah saat bongkar muat dari supplier."
                          class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                          style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">{{ old('alasan') }}</textarea>
            </div>

            {{-- Foto bukti --}}
            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Foto bukti</label>
                <div class="rounded-xl p-3" style="border:1.5px dashed #DDD3BE; background-color:#ffffff;">
                    <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png"
                           @change="handleFile($event)"
                           class="w-full text-xs cursor-pointer" style="color:#5a7398;">
                </div>
                <p class="mt-1.5 text-xs" style="color:#8a9bb5;">Format JPG atau PNG, maksimal 2 MB.</p>

                <div x-show="fileName !== ''" style="display:none;"
                     class="mt-2.5 flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl"
                     :style="fileValid ? 'background-color:#dcfce7; color:#166534;' : 'background-color:#fee2e2; color:#991b1b;'">
                    <i data-lucide="image" class="w-4 h-4 shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold truncate" x-text="fileName"></p>
                        <p class="text-[11px]" x-text="fileInfo"></p>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl text-sm font-semibold transition-colors cursor-pointer"
                    style="background-color:#162447; color:#F7F1E5;"
                    onmouseover="this.style.backgroundColor='#1e3060';"
                    onmouseout="this.style.backgroundColor='#162447';">
                Simpan pencatatan
            </button>

            <p class="text-xs" style="color:#8a9bb5;">
                Stok barang disesuaikan otomatis saat pencatatan disimpan.
            </p>
        </form>
    </div>

    {{-- ============================================================
         KOLOM KANAN — Riwayat pencatatan (7/12)
    ============================================================ --}}
    <div class="lg:col-span-7 bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">

        {{-- Header + Filter Pill --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h2 class="text-lg font-bold" style="color:#162447;">Riwayat pencatatan</h2>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('rusak-retur.index') }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                   style="{{ !request('tipe') ? 'background-color:#162447; color:#F7F1E5;' : 'border:1px solid #DDD3BE; color:#5a7398;' }}">
                    Semua
                </a>
                <a href="{{ route('rusak-retur.index', ['tipe' => 'rusak']) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                   style="{{ request('tipe') === 'rusak' ? 'background-color:#162447; color:#F7F1E5;' : 'border:1px solid #DDD3BE; color:#5a7398;' }}">
                    Rusak
                </a>
                <a href="{{ route('rusak-retur.index', ['tipe' => 'retur']) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                   style="{{ request('tipe') === 'retur' ? 'background-color:#162447; color:#F7F1E5;' : 'border:1px solid #DDD3BE; color:#5a7398;' }}">
                    Retur
                </a>
            </div>
        </div>

        {{-- Tabel data --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs" style="color:#8a9bb5; border-bottom:1px solid #EDE5D4;">
                        <th class="pb-3 pr-3 font-medium">No.</th>
                        <th class="pb-3 px-2.5 font-medium">Jenis</th>
                        <th class="pb-3 px-2.5 font-medium">Barang</th>
                        <th class="pb-3 px-2.5 font-medium">Jumlah</th>
                        <th class="pb-3 px-2.5 font-medium">Tanggal</th>
                        <th class="pb-3 px-2.5 font-medium">Dicatat oleh</th>
                        <th class="pb-3 pl-2.5 font-medium">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#F7F1E5;">
                    @forelse ($data as $item)
                        <tr class="transition-colors"
                            onmouseover="this.style.backgroundColor='#FAF6EE';"
                            onmouseout="this.style.backgroundColor='';">
                            <td class="whitespace-nowrap py-3.5 pr-3 text-xs font-medium" style="color:#8a9bb5;">
                                RR-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="whitespace-nowrap py-3.5 px-2.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="{{ $item->tipe === 'rusak' ? 'background-color:#fee2e2; color:#991b1b;' : 'background-color:#dbeafe; color:#1d4ed8;' }}">
                                    {{ ucfirst($item->tipe) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-2.5">
                                <p class="font-semibold" style="color:#162447;">{{ $item->barang?->nama_barang ?? '-' }}</p>
                                @if($item->alasan)
                                    <p class="text-xs mt-0.5 line-clamp-1" style="color:#8a9bb5;">{{ $item->alasan }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap py-3.5 px-2.5 tabular-nums" style="color:#162447;">
                                {{ $item->jumlah }} pcs
                            </td>
                            <td class="whitespace-nowrap py-3.5 px-2.5 text-xs" style="color:#5a7398;">
                                {{ $item->tanggal->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap py-3.5 px-2.5 text-xs" style="color:#5a7398;">
                                {{ $item->user?->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap py-3.5 pl-2.5">
                                <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank"
                                   class="text-xs font-semibold underline" style="color:#162447;">
                                    Lihat foto
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-sm" style="color:#8a9bb5;">
                                Belum ada catatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($data->total() > 0)
            <div class="mt-4 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="border-top:1px solid #EDE5D4;">
                <p class="text-xs" style="color:#8a9bb5;">
                    Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} pencatatan
                </p>
                @if ($data->hasPages())
                    <div>
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
