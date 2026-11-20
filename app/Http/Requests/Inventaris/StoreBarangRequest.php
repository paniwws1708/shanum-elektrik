<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin() || $this->user()->isStaffBarang();
    }

    public function rules()
    {
        return [
            'kode_barang'        => ['required', 'string', 'max:255', 'unique:barangs,kode_barang'],
            'nama_barang'        => ['required', 'string', 'max:255'],
            'kategori_id'        => ['required', 'exists:kategoris,id'],
            'harga_modal'        => ['required', 'numeric', 'min:0'],
            'harga_jual'         => ['required', 'numeric', 'min:0', 'gte:harga_modal'],
            'stok'               => ['required', 'integer', 'min:0'],
            'stok_minimum'       => ['nullable', 'integer', 'min:0'],
            'lokasi_penyimpanan' => ['nullable', 'string', 'max:255'],
            'foto'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',
            'harga_modal.required' => 'Harga modal wajib diisi.',
            'harga_modal.min'      => 'Harga modal tidak boleh negatif.',
            'harga_jual.required'  => 'Harga jual wajib diisi.',
            'harga_jual.min'       => 'Harga jual tidak boleh negatif.',
            'harga_jual.gte'       => 'Harga jual tidak boleh lebih rendah dari harga modal.',
            'stok.required'        => 'Stok awal wajib diisi.',
            'stok.integer'         => 'Stok harus berupa angka bulat.',
            'stok.min'             => 'Stok tidak boleh negatif.',
            'foto.image'           => 'File foto harus berupa gambar.',
            'foto.mimes'           => 'Format foto yang diperbolehkan: jpg, jpeg, png, webp.',
            'foto.max'             => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
