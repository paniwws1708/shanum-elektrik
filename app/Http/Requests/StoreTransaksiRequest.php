<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items'          => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty'    => 'required|integer|min:1',
            'bayar'          => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'           => 'Keranjang tidak boleh kosong.',
            'items.min'                => 'Keranjang tidak boleh kosong.',
            'items.*.barang_id.exists' => 'Barang tidak ditemukan.',
            'items.*.qty.min'          => 'Jumlah minimal 1.',
            'bayar.required'           => 'Masukkan jumlah uang pembayaran.',
        ];
    }
}
