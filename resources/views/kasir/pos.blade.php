{{-- ════════════════════════════════════════════════════════════════
     HALAMAN POS (Kasir) — Pilih barang, atur keranjang, bayar
     ════════════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Transaksi Baru — Shanum Elektrik')
@section('page-title', 'Transaksi Baru')
@section('breadcrumb', 'Shanum Elektrik / Penjualan / Transaksi')

@section('content')
{{-- Alpine.js data untuk seluruh halaman POS --}}
<div x-data="posApp()" x-cloak>

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color:#162447;">Transaksi Baru</h1>
            <p class="mt-1 text-sm" style="color:#5a7398;">
                Kasir: {{ auth()->user()->name }} &middot; {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-semibold tabular-nums"
              style="background-color:#EDE5D4; color:#162447;">
            {{ \App\Models\Transaksi::buatNoInvoice() }}
        </span>
    </div>

    {{-- Pesan error validasi --}}
    @if($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl text-sm" style="background-color:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        {{-- ══════════════════════════════════════════════════════════
             KOLOM KIRI — Daftar Barang (7/12 lebar di desktop)
             ══════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- Bar pencarian & filter kategori --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-7">
                    <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Cari barang</label>
                    <input type="text" x-model="cari" placeholder="Nama atau kode barang"
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                           style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                           onfocus="this.style.borderColor='#162447';"
                           onblur="this.style.borderColor='#DDD3BE';">
                </div>
                <div class="sm:col-span-5">
                    <label class="block text-xs font-medium mb-1.5" style="color:#5a7398;">Kategori</label>
                    <select x-model="filterKategori"
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm focus:outline-none"
                            style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                        <option value="">Semua kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Grid kartu barang (2 kolom seperti PDF, 6 item per halaman) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <template x-for="barang in barangPaginated" :key="barang.id">
                    <div class="bg-white rounded-2xl p-4 flex flex-col justify-between transition-all duration-150"
                         style="border:1px solid #EDE5D4;">

                        <div>
                            <p class="text-xs truncate" style="color:#8a9bb5;"
                               x-text="(barang.kode_barang ? barang.kode_barang + ' · ' : '') + (barang.kategori ? barang.kategori.nama : '-')"></p>
                            <p class="text-sm font-semibold mt-1 truncate" style="color:#162447;" x-text="barang.nama_barang"></p>
                            <p class="text-base font-bold mt-2 tabular-nums" style="color:#162447;" x-text="formatRupiah(barang.harga_jual)"></p>

                            <div class="mt-2.5 mb-3.5">
                                <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-medium"
                                      :style="badgeStyle(barang.status_stok_label)">
                                    <span x-text="'Stok ' + barang.stok"></span>
                                </span>
                            </div>
                        </div>

                        <button type="button"
                                @click="tambahKeKeranjang(barang)"
                                class="w-full py-2.5 px-3 rounded-xl text-xs font-semibold transition-colors cursor-pointer"
                                style="background-color:#162447; color:#F7F1E5;"
                                onmouseover="this.style.backgroundColor='#1e3060';"
                                onmouseout="this.style.backgroundColor='#162447';">
                            Tambah ke keranjang
                        </button>
                    </div>
                </template>
            </div>

            {{-- Jika tidak ada barang --}}
            <div x-show="barangFiltered.length === 0" class="bg-white rounded-2xl p-8 text-center" style="border:1px solid #EDE5D4;">
                <p class="text-sm" style="color:#8a9bb5;">Tidak ada barang yang cocok.</p>
            </div>

            {{-- Pagination daftar barang --}}
            <div x-show="barangFiltered.length > perPage"
                 class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <p class="text-xs" style="color:#5a7398;"
                   x-text="'Menampilkan ' + ((currentPage - 1) * perPage + 1) + '–' + Math.min(currentPage * perPage, barangFiltered.length) + ' dari ' + barangFiltered.length + ' barang'"></p>
                <div class="flex items-center gap-1.5">
                    <button type="button"
                            @click="if (currentPage > 1) currentPage--"
                            :disabled="currentPage <= 1"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                            style="border:1px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                        Sebelumnya
                    </button>
                    <span class="px-2.5 py-1.5 text-xs font-semibold tabular-nums" style="color:#162447;"
                          x-text="currentPage + ' / ' + totalPages"></span>
                    <button type="button"
                            @click="if (currentPage < totalPages) currentPage++"
                            :disabled="currentPage >= totalPages"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                            style="border:1px solid #DDD3BE; background-color:#ffffff; color:#162447;">
                        Berikutnya
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             KOLOM KANAN — Keranjang & Pembayaran (5/12 lebar)
             ══════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl p-5" style="border:1px solid #EDE5D4;">

                {{-- Header keranjang --}}
                <h2 class="text-lg font-bold mb-3" style="color:#162447;">Keranjang</h2>

                {{-- Daftar item di keranjang --}}
                <div class="divide-y max-h-80 overflow-y-auto" style="border-color:#F7F1E5;">
                    <template x-for="(item, index) in keranjang" :key="item.barang_id">
                        <div class="py-3.5">
                            <div class="flex justify-between items-start gap-2">
                                <p class="text-sm font-semibold" style="color:#162447;" x-text="item.nama"></p>
                                <p class="text-sm font-bold whitespace-nowrap tabular-nums" style="color:#162447;" x-text="formatRupiah(item.harga * item.qty)"></p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs" style="color:#8a9bb5;" x-text="formatRupiah(item.harga) + ' per pcs'"></p>
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            @click="kurangiQty(index)"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors cursor-pointer"
                                            style="border:1px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                                            onmouseover="this.style.backgroundColor='#FAF6EE';"
                                            onmouseout="this.style.backgroundColor='#ffffff';">&minus;</button>
                                    <span class="text-sm font-semibold w-5 text-center tabular-nums" style="color:#162447;" x-text="item.qty"></span>
                                    <button type="button"
                                            @click="tambahQty(index)"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors cursor-pointer disabled:opacity-40"
                                            style="border:1px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                                            onmouseover="if(!this.disabled) this.style.backgroundColor='#FAF6EE';"
                                            onmouseout="this.style.backgroundColor='#ffffff';"
                                            :disabled="item.qty >= item.stok">+</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Keranjang kosong --}}
                <div x-show="keranjang.length === 0" class="py-8 text-center">
                    <p class="text-sm" style="color:#8a9bb5;">Keranjang masih kosong</p>
                </div>

                {{-- Total & Pembayaran --}}
                <div class="pt-4 mt-2 space-y-3" style="border-top:1px solid #EDE5D4;">
                    {{-- Subtotal --}}
                    <div class="flex justify-between items-center text-xs" style="color:#5a7398;">
                        <span x-text="'Subtotal (' + totalItem + ' item)'"></span>
                        <span class="font-semibold tabular-nums" style="color:#162447;" x-text="formatRupiah(totalHarga)"></span>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center">
                        <span class="text-base font-bold" style="color:#162447;">Total</span>
                        <span class="text-lg font-bold tabular-nums" style="color:#162447;" x-text="formatRupiah(totalHarga)"></span>
                    </div>

                    {{-- Input uang tunai --}}
                    <div>
                        <label class="text-xs font-medium mb-1.5 block" style="color:#5a7398;">Uang tunai diterima</label>
                        <input type="number" x-model.number="bayar" min="0" placeholder="0"
                               class="w-full px-3.5 py-2.5 rounded-xl text-sm font-bold tabular-nums focus:outline-none"
                               style="border:1.5px solid #DDD3BE; background-color:#ffffff; color:#162447;"
                               onfocus="this.style.borderColor='#162447';"
                               onblur="this.style.borderColor='#DDD3BE';">
                    </div>

                    {{-- Kembalian --}}
                    <div class="flex justify-between items-center px-3.5 py-2.5 rounded-xl"
                         :style="kembalian >= 0 ? 'background-color:#dcfce7;' : 'background-color:#fee2e2;'">
                        <span class="text-xs font-medium" :style="kembalian >= 0 ? 'color:#166534;' : 'color:#991b1b;'">Kembalian</span>
                        <span class="text-sm font-bold tabular-nums"
                              :style="kembalian >= 0 ? 'color:#166534;' : 'color:#991b1b;'"
                              x-text="formatRupiah(kembalian)"></span>
                    </div>

                    {{-- Tombol Bayar --}}
                    <form method="POST" action="{{ route('kasir.store') }}" x-ref="formBayar">
                        @csrf
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
                            Bayar dan simpan transaksi
                        </button>
                    </form>

                    <p class="text-xs leading-relaxed" style="color:#8a9bb5;">
                        Setelah disimpan, stok barang berkurang otomatis dan transaksi masuk ke riwayat.
                    </p>
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

        // State pencarian, filter & pagination
        cari: '',
        filterKategori: '',
        currentPage: 1,
        perPage: 6,

        init() {
            this.$watch('cari', () => { this.currentPage = 1; });
            this.$watch('filterKategori', () => { this.currentPage = 1; });
        },

        // Keranjang belanja
        keranjang: [],

        // Pembayaran
        bayar: 0,

        // ─── Computed: barang yang sudah difilter ───
        get barangFiltered() {
            let hasil = this.semuaBarang;
            if (this.cari) {
                const q = this.cari.toLowerCase();
                hasil = hasil.filter(b =>
                    b.nama_barang.toLowerCase().includes(q) ||
                    b.kode_barang.toLowerCase().includes(q)
                );
            }
            if (this.filterKategori) {
                const katId = parseInt(this.filterKategori);
                hasil = hasil.filter(b => b.kategori_id === katId);
            }
            return hasil;
        },

        // ─── Computed: pagination daftar barang ───
        get totalPages() {
            return Math.max(1, Math.ceil(this.barangFiltered.length / this.perPage));
        },
        get barangPaginated() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.barangFiltered.slice(start, start + this.perPage);
        },

        // ─── Computed: total item ───
        get totalItem() {
            return this.keranjang.reduce((sum, item) => sum + item.qty, 0);
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
            return 'background-color:#dcfce7; color:#166534;';
        }
    }
}
</script>
@endsection
