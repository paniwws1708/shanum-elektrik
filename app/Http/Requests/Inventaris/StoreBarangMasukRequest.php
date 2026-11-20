<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangMasukRequest extends FormRequest
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
            'supplier_id' => 'nullable|exists:suppliers,id',
            'jumlah' => 'required|integer|min:1',
            'harga_modal_satuan' => 'nullable|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang harus dipilih.',
            'barang_id.exists' => 'Barang tidak ditemukan.',
            'supplier_id.exists' => 'Supplier tidak ditemukan.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga_modal_satuan.numeric' => 'Harga modal harus berupa angka.',
            'tanggal_masuk.required' => 'Tanggal barang masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal barang masuk tidak valid.',
        ];
    }
}