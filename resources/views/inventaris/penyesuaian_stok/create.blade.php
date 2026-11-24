
@extends('layouts.app')

@section('title', 'Penyesuaian Stok - Shanum Elektrik')
@section('page-title', 'Penyesuaian Stok')
@section('breadcrumb', 'Inventaris / Penyesuaian Stok / Tambah')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-gray-900">
            Form Penyesuaian Stok
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Sesuaikan jumlah stok sistem dengan hasil perhitungan fisik barang.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:p-7">
        <form action="{{ route('inventaris.penyesuaian_stok.store') }}"
              method="POST"
              class="space-y-5">
            @csrf

            {{-- Pilih Barang --}}
            <div>
                <label for="barang_id"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Pilih Barang <span class="text-red-500">*</span>
                </label>

                <select name="barang_id"
                        id="barang_id"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Pilih Barang --</option>

                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}"
                                data-stok="{{ $barang->stok }}"
                                data-kode="{{ $barang->kode_barang }}"
                                {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>

                @error('barang_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Informasi Stok Sistem --}}
            <div class="rounded-xl border border-gray-300 p-4">
                <p class="text-sm font-medium text-gray-700">
                    Stok saat ini di sistem
                </p>

                <div class="mt-2 flex items-baseline gap-2">
                    <span id="stok_sistem"
                          class="text-3xl font-bold text-gray-900">-</span>
                    <span class="text-sm text-gray-500">unit</span>
                </div>

                <p class="mt-1 text-xs text-gray-500">
                    Kode barang:
                    <span id="kode_barang">-</span>
                </p>
            </div>

            {{-- Input Stok Fisik --}}
            <div>
                <label for="stok_fisik"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Jumlah Stok Fisik <span class="text-red-500">*</span>
                </label>

                <input type="number"
                       name="stok_fisik"
                       id="stok_fisik"
                       min="0"
                       step="1"
                       value="{{ old('stok_fisik') }}"
                       required
                       placeholder="Masukkan jumlah stok fisik"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

                <p class="mt-1 text-xs text-gray-500">
                    Masukkan jumlah barang yang benar-benar tersedia secara fisik.
                </p>

                @error('stok_fisik')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pratinjau Penyesuaian --}}
            <div id="hasil_penyesuaian"
                 class="hidden rounded-xl border border-gray-200 bg-white p-4">
                <p class="mb-3 text-sm font-semibold text-gray-700">
                    Pratinjau Penyesuaian
                </p>

                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-gray-500">Selisih stok</span>

                    <span id="selisih_stok"
                          class="rounded-full px-3 py-1 text-sm font-bold">
                        0
                    </span>
                </div>

                <p id="keterangan_selisih"
                   class="mt-2 text-xs text-gray-500"></p>
            </div>

            {{-- Alasan --}}
            <div>
                <label for="keterangan"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Alasan Penyesuaian <span class="text-red-500">*</span>
                </label>

                <textarea name="keterangan"
                          id="keterangan"
                          rows="3"
                          maxlength="500"
                          required
                          placeholder="Contoh: Penyesuaian berdasarkan hasil perhitungan fisik barang."
                          class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('keterangan') }}</textarea>

                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('inventaris.penyesuaian_stok.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90"
                        style="background:#162447;">
                    Simpan Penyesuaian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const barangSelect = document.getElementById('barang_id');
    const stokFisikInput = document.getElementById('stok_fisik');
    const stokSistemText = document.getElementById('stok_sistem');
    const kodeBarangText = document.getElementById('kode_barang');
    const hasilBox = document.getElementById('hasil_penyesuaian');
    const selisihText = document.getElementById('selisih_stok');
    const keteranganText = document.getElementById('keterangan_selisih');

    function hitungPenyesuaian() {
        const option = barangSelect.options[barangSelect.selectedIndex];

        if (!option || !option.value) {
            stokSistemText.textContent = '-';
            kodeBarangText.textContent = '-';
            hasilBox.classList.add('hidden');
            return;
        }

        const stokSistem = Number(option.dataset.stok);

        stokSistemText.textContent = stokSistem.toLocaleString('id-ID');
        kodeBarangText.textContent = option.dataset.kode || '-';

        if (stokFisikInput.value === '') {
            hasilBox.classList.add('hidden');
            return;
        }

        const stokFisik = Number(stokFisikInput.value);

        if (!Number.isInteger(stokFisik) || stokFisik < 0) {
            hasilBox.classList.add('hidden');
            return;
        }

        const selisih = stokFisik - stokSistem;

        hasilBox.classList.remove('hidden');

        selisihText.className = 'rounded-full px-3 py-1 text-sm font-bold';

        if (selisih > 0) {
            selisihText.textContent = '+' + selisih.toLocaleString('id-ID');
            selisihText.classList.add('bg-green-100', 'text-green-700');

            keteranganText.textContent =
                'Stok fisik lebih banyak. Sistem akan menambahkan ' +
                selisih + ' unit.';
        } else if (selisih < 0) {
            selisihText.textContent = selisih.toLocaleString('id-ID');
            selisihText.classList.add('bg-red-100', 'text-red-700');

            keteranganText.textContent =
                'Stok fisik lebih sedikit. Sistem akan mengurangi ' +
                Math.abs(selisih) + ' unit.';
        } else {
            selisihText.textContent = '0';
            selisihText.classList.add('bg-gray-100', 'text-gray-700');

            keteranganText.textContent =
                'Stok fisik sudah sesuai dengan stok di sistem. Tidak ada perubahan.';
        }
    }

    barangSelect.addEventListener('change', hitungPenyesuaian);
    stokFisikInput.addEventListener('input', hitungPenyesuaian);

    hitungPenyesuaian();
});
</script>
@endsection
