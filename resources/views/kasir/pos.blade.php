{{-- ════════════════════════════════════════════════════════════════
     HALAMAN POS (Kasir) — Pilih barang, atur keranjang, bayar
     ════════════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Transaksi Kasir — Shanum Elektrik')
@section('page-title', 'Transaksi Kasir')
@section('breadcrumb', 'Shanum Elektrik / Penjualan / Transaksi')

@section('content')
{{-- Alpine.js data untuk seluruh halaman POS --}}
<div x-data="posApp()" x-cloak>

    {{-- Pesan error validasi --}}
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ══════════════════════════════════════════════════════════
             KOLOM KIRI — Daftar Barang (2/3 lebar di desktop)
             ══════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Bar pencarian & filter kategori --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" fill="none" stroke="#8a9bb5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="cari" placeholder="Cari barang..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm border focus:outline-none focus:ring-2"
                           style="border-color:#EDE5D4; focus:ring-color:#162447;">
                </div>
                <select x-model="filterKategori"
                        class="px-4 py-2.5 rounded-xl text-sm border focus:outline-none"
                        style="border-color:#EDE5D4;">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Grid kartu barang --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                <template x-for="barang in barangFiltered" :key="barang.id">
                    <div @click="tambahKeKeranjang(barang)"
                         class="bg-white rounded-xl p-3 shadow-sm cursor-pointer transition-all duration-150 hover:shadow-md"
                         style="border:1px solid #EDE5D4;"
                         :class="{ 'ring-2': false }"
                         onmouseover="this.style.borderColor='#162447';"
                         onmouseout="this.style.borderColor='#EDE5D4';">

                        {{-- Badge stok --}}
                        <div class="flex justify-end mb-2">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                  :style="badgeStyle(barang.status_stok_label)">
                                <span x-text="'Stok: ' + barang.stok"></span>
                            </span>
                        </div>

                        <p class="text-sm font-semibold truncate" style="color:#162447;" x-text="barang.nama_barang"></p>
                        <p class="text-xs mt-0.5 truncate" style="color:#8a9bb5;" x-text="barang.kategori ? barang.kategori.nama : '-'"></p>
                        <p class="text-sm font-bold mt-2" style="color:#162447;" x-text="formatRupiah(barang.harga_jual)"></p>
                    </div>
                </template>
            </div>

            {{-- Jika tidak ada barang --}}
            <div x-show="barangFiltered.length === 0" class="text-center py-12">
                <p class="text-sm" style="color:#8a9bb5;">Tidak ada barang yang cocok.</p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             KOLOM KANAN — Keranjang & Pembayaran (1/3 lebar)
             ══════════════════════════════════════════════════════════ --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #EDE5D4;">

                {{-- Header keranjang --}}
                <div class="px-5 py-4" style="background-color:#162447;">
                    <h3 class="text-sm font-semibold" style="color:#F7F1E5;">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        Keranjang
                        <span class="ml-1 text-xs font-normal" style="color:#8a9bb5;" x-text="'(' + keranjang.length + ' item)'"></span>
                    </h3>
                </div>

                {{-- Daftar item di keranjang --}}
                <div class="divide-y max-h-80 overflow-y-auto" style="border-color:#EDE5D4;">
                    <template x-for="(item, index) in keranjang" :key="item.barang_id">
                        <div class="px-4 py-3">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0 mr-2">
                                    <p class="text-sm font-medium truncate" style="color:#162447;" x-text="item.nama"></p>
                                    <p class="text-xs" style="color:#8a9bb5;" x-text="formatRupiah(item.harga) + ' × ' + item.qty"></p>
                                </div>
                                <p class="text-sm font-semibold whitespace-nowrap" style="color:#162447;" x-text="formatRupiah(item.harga * item.qty)"></p>
                            </div>
                            {{-- Tombol +/- dan hapus --}}
                            <div class="flex items-center gap-2 mt-2">
                                <button @click="kurangiQty(index)" class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-bold transition-colors cursor-pointer"
                                        style="background-color:#F7F1E5; color:#162447;"
                                        onmouseover="this.style.backgroundColor='#EDE5D4';"
                                        onmouseout="this.style.backgroundColor='#F7F1E5';">−</button>
                                <span class="text-sm font-semibold w-6 text-center" style="color:#162447;" x-text="item.qty"></span>
                                <button @click="tambahQty(index)" class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-bold transition-colors cursor-pointer"
                                        style="background-color:#F7F1E5; color:#162447;"
                                        onmouseover="this.style.backgroundColor='#EDE5D4';"
                                        onmouseout="this.style.backgroundColor='#F7F1E5';"
                                        :disabled="item.qty >= item.stok">+</button>
                                <button @click="hapusItem(index)" class="ml-auto text-xs px-2 py-1 rounded-lg transition-colors cursor-pointer"
                                        style="color:#991b1b; background-color:#fef2f2;"
                                        onmouseover="this.style.backgroundColor='#fecaca';"
                                        onmouseout="this.style.backgroundColor='#fef2f2';">Hapus</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Keranjang kosong --}}
                <div x-show="keranjang.length === 0" class="px-4 py-8 text-center">
                    <p class="text-sm" style="color:#8a9bb5;">Keranjang masih kosong</p>
                </div>

                {{-- Total & Pembayaran --}}
                <div class="px-4 py-4 space-y-3" style="border-top:1px solid #EDE5D4;">
                    {{-- Total --}}
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold" style="color:#162447;">Total</span>
                        <span class="text-lg font-bold" style="color:#162447;" x-text="formatRupiah(totalHarga)"></span>
                    </div>

                    {{-- Input uang tunai --}}
                    <div>
                        <label class="text-xs font-medium mb-1 block" style="color:#8a9bb5;">Uang Tunai (Rp)</label>
                        <input type="number" x-model.number="bayar" min="0" placeholder="0"
                               class="w-full px-3 py-2.5 rounded-xl text-sm border text-right font-semibold focus:outline-none focus:ring-2"
                               style="border-color:#EDE5D4; color:#162447;">
                    </div>

                    {{-- Kembalian --}}
                    <div class="flex justify-between items-center px-3 py-2 rounded-xl" style="background-color:#F7F1E5;">
                        <span class="text-sm" style="color:#8a9bb5;">Kembalian</span>
                        <span class="text-sm font-bold"
                              :style="kembalian >= 0 ? 'color:#059669;' : 'color:#dc2626;'"
                              x-text="formatRupiah(kembalian)"></span>
                    </div>

                    {{-- Tombol Bayar --}}
                    <form method="POST" action="{{ route('kasir.store') }}" x-ref="formBayar">
                        @csrf
                        {{-- Hidden inputs di-generate oleh Alpine --}}
                        <template x-for="(item, i) in keranjang" :key="'f'+item.barang_id">
                            <div>
                                <input type="hidden" :name="'items['+i+'][barang_id]'" :value="item.barang_id">
                                <input type="hidden" :name="'items['+i+'][qty]'" :value="item.qty">
                            </div>
                        </template>
                        <input type="hidden" name="bayar" :value="bayar">

                        <button type="submit"
                                :disabled="!bisaBayar"
                                class="w-full py-3 rounded-xl text-sm font-semibold transition-all duration-150 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                                style="background-color:#162447; color:#F7F1E5;"
                                onmouseover="if(!this.disabled) this.style.backgroundColor='#1e3060';"
                                onmouseout="this.style.backgroundColor='#162447';">
                            Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     Alpine.js Logic — POS App
     ═══════════════════════════════════════════════════════════════ --}}
<script>
function posApp() {
    return {
        // Data barang dari server (di-pass sebagai JSON)
        semuaBarang: @json($barangs),
        kategoris: @json($kategoris),

        // State pencarian & filter
        cari: '',
        filterKategori: '',

        // Keranjang belanja
        keranjang: [],

        // Pembayaran
        bayar: 0,

        // ─── Computed: barang yang sudah difilter ───
        get barangFiltered() {
            let hasil = this.semuaBarang;
            // Filter berdasarkan pencarian
            if (this.cari) {
                const q = this.cari.toLowerCase();
                hasil = hasil.filter(b =>
                    b.nama_barang.toLowerCase().includes(q) ||
                    b.kode_barang.toLowerCase().includes(q)
                );
            }
            // Filter berdasarkan kategori
            if (this.filterKategori) {
                const katId = parseInt(this.filterKategori);
                hasil = hasil.filter(b => b.kategori_id === katId);
            }
            return hasil;
        },

        // ─── Computed: total harga ───
        get totalHarga() {
            return this.keranjang.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        },

        // ─── Computed: kembalian ───
        get kembalian() {
            return (this.bayar || 0) - this.totalHarga;
        },

        // ─── Computed: tombol bayar aktif? ───
        get bisaBayar() {
            return this.keranjang.length > 0 && this.bayar >= this.totalHarga && this.totalHarga > 0;
        },

        // ─── Method: tambah barang ke keranjang ───
        tambahKeKeranjang(barang) {
            const existing = this.keranjang.find(k => k.barang_id === barang.id);
            if (existing) {
                // Jangan melebihi stok
                if (existing.qty < barang.stok) {
                    existing.qty++;
                }
            } else {
                this.keranjang.push({
                    barang_id: barang.id,
                    nama: barang.nama_barang,
                    harga: parseFloat(barang.harga_jual),
                    qty: 1,
                    stok: barang.stok,
                });
            }
        },

        tambahQty(index) {
            if (this.keranjang[index].qty < this.keranjang[index].stok) {
                this.keranjang[index].qty++;
            }
        },
        kurangiQty(index) {
            if (this.keranjang[index].qty > 1) {
                this.keranjang[index].qty--;
            } else {
                this.hapusItem(index);
            }
        },
        hapusItem(index) {
            this.keranjang.splice(index, 1);
        },

        // ─── Formatter Rupiah ───
        formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        },

        // ─── Badge stok sesuai status_stok ───
        badgeStyle(status) {
            if (status === 'menipis') return 'background-color:#fef3c7; color:#92400e;';
            return 'background-color:#ecfdf5; color:#059669;'; // aman
        }
    }
}
</script>
@endsection
