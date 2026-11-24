<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class StorePenyesuaianStokRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (
            $this->user()->isAdmin() ||
            $this->user()->isStaffBarang()
        );
    }

    public function rules(): array
    {
        return [
            'barang_id' => 'required|exists:barangs,id',
            'stok_fisik' => 'required|integer|min:0',
            'keterangan' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang harus dipilih.',
            'barang_id.exists' => 'Barang tidak ditemukan.',
            'stok_fisik.required' => 'Stok fisik wajib diisi.',
            'stok_fisik.integer' => 'Stok fisik harus berupa angka bulat.',
            'stok_fisik.min' => 'Stok fisik tidak boleh kurang dari 0.',
            'keterangan.required' => 'Alasan penyesuaian wajib diisi.',
            'keterangan.max' => 'Alasan maksimal 500 karakter.',
        ];
    }
}
