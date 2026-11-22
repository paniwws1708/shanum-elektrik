<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    /**
     * Memeriksa hak akses pengguna.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && (
            $user->isAdmin() ||
            $user->isStaffBarang()
        );
    }

    /**
     * Aturan validasi data barang.
     */
    public function rules(): array
    {
        return [
            'kode_barang' => [
                'required',
                'string',
                'max:255',
                'unique:barangs,kode_barang',
            ],

            'nama_barang' => [
                'required',
                'string',
                'max:255',
            ],

            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],

            'harga_modal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'harga_jual' => [
                'required',
                'numeric',
                'min:0',
                'gte:harga_modal',
            ],

            'stok' => [
                'required',
                'integer',
                'min:0',
            ],

            'stok_minimum' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'lokasi_penyimpanan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi.
     */
    public function messages(): array
    {
        return [
            // Kode barang
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.string' => 'Kode barang harus berupa teks.',
            'kode_barang.max' => 'Kode barang maksimal 255 karakter.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',

            // Nama barang
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.string' => 'Nama barang harus berupa teks.',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter.',

            // Kategori
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.integer' => 'Kategori yang dipilih tidak valid.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak ditemukan.',

            // Harga modal
            'harga_modal.required' => 'Harga modal wajib diisi.',
            'harga_modal.numeric' => 'Harga modal harus berupa angka.',
            'harga_modal.min' => 'Harga modal tidak boleh negatif.',

            // Harga jual
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih rendah dari harga modal.',

            // Stok
            'stok.required' => 'Stok awal wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',

            // Stok minimum
            'stok_minimum.integer' => 'Stok minimum harus berupa angka bulat.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',

            // Lokasi penyimpanan
            'lokasi_penyimpanan.string' => 'Lokasi penyimpanan harus berupa teks.',
            'lokasi_penyimpanan.max' => 'Lokasi penyimpanan maksimal 255 karakter.',

            // Foto
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diperbolehkan: jpg, jpeg, png, webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
