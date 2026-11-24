<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBarangRequest extends FormRequest
{
    /**
     * Memastikan pengguna memiliki hak untuk mengubah data barang.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && ($user->isAdmin() || $user->isStaffBarang());
    }

    /**
     * Aturan validasi saat memperbarui data barang.
     */
    public function rules(): array
    {
        // Mendukung route model binding maupun parameter berupa ID.
        $barang = $this->route('barang');

        $barangId = $barang instanceof Model
            ? $barang->getKey()
            : $barang;

        return [
            'kode_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barangs', 'kode_barang')->ignore($barangId),
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

            // Stok tidak diubah melalui form edit.
            // Gunakan Barang Masuk atau Penyesuaian Stok.
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
     * Pesan khusus untuk setiap aturan validasi.
     */
    public function messages(): array
    {
        return [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.string' => 'Kode barang harus berupa teks.',
            'kode_barang.max' => 'Kode barang maksimal 255 karakter.',
            'kode_barang.unique' => 'Kode barang sudah digunakan oleh barang lain.',

            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.string' => 'Nama barang harus berupa teks.',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter.',

            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.integer' => 'Kategori yang dipilih tidak valid.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak ditemukan.',

            'harga_modal.required' => 'Harga modal wajib diisi.',
            'harga_modal.numeric' => 'Harga modal harus berupa angka.',
            'harga_modal.min' => 'Harga modal tidak boleh negatif.',

            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih rendah dari harga modal.',

            'stok_minimum.integer' => 'Stok minimum harus berupa bilangan bulat.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',

            'lokasi_penyimpanan.string' => 'Lokasi penyimpanan harus berupa teks.',
            'lokasi_penyimpanan.max' => 'Lokasi penyimpanan maksimal 255 karakter.',

            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diperbolehkan: jpg, jpeg, png, webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
