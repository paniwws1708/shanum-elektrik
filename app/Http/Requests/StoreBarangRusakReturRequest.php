<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRusakReturRequest extends FormRequest
{
    // Boleh atau tidak. Pengecekan role sudah kita lakukan di controller,
    // jadi di sini cukup true.
    public function authorize(): bool
    {
        return true;
    }

    // Checkbox yang tidak dicentang tidak ikut terkirim oleh browser.
    // Jadi sebelum validasi, kita ubah menjadi true/false yang pasti.
    protected function prepareForValidation(): void
    {
        $this->merge([
            'kembali_ke_stok' => $this->boolean('kembali_ke_stok'),
        ]);
    }

    // Aturan validasi tiap kolom.
    public function rules(): array
    {
        return [
            'tipe'            => ['required', 'in:rusak,retur'],
            'barang_id'       => ['required', 'exists:barangs,id'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'tanggal'         => ['required', 'date', 'before_or_equal:today'],
            'alasan'          => ['required', 'string', 'max:500'],

            // Validasi foto: harus gambar, hanya jpg/jpeg/png, maksimal 2 MB (2048 KB).
            // Kalau foto mau dibuat tidak wajib, ganti 'required' menjadi 'nullable'
            // (dan ubah juga kolom foto_bukti di migration jadi ->nullable()).
            'foto_bukti'      => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Khusus retur (boleh kosong). Nomor invoice harus ada di tabel transaksis.
            'no_invoice'      => ['nullable', 'string', 'exists:transaksis,no_invoice'],
            'nama_pembeli'    => ['nullable', 'string', 'max:100'],
            'kembali_ke_stok' => ['boolean'],
        ];
    }

    // Pesan error dalam bahasa Indonesia.
    public function messages(): array
    {
        return [
            'tipe.required'        => 'Pilih jenis catatan (rusak atau retur).',
            'barang_id.required'   => 'Pilih barang terlebih dahulu.',
            'barang_id.exists'     => 'Barang tidak ditemukan.',
            'jumlah.required'      => 'Jumlah wajib diisi.',
            'jumlah.min'           => 'Jumlah minimal 1.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'alasan.required'      => 'Alasan wajib diisi.',
            'foto_bukti.required'  => 'Foto bukti wajib diunggah.',
            'foto_bukti.image'     => 'File harus berupa gambar.',
            'foto_bukti.mimes'     => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto_bukti.max'       => 'Ukuran foto maksimal 2 MB.',
            'no_invoice.exists'    => 'Nomor invoice tidak ditemukan di data transaksi.',
        ];
    }
}