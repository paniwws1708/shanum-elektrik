<?php

namespace App\Http\Requests;

use App\Models\Pengeluaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePengeluaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            // Kategori harus salah satu dari daftar di model Pengeluaran::KATEGORI.
            'kategori'   => ['required', Rule::in(array_keys(Pengeluaran::KATEGORI))],
            'jumlah'     => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'keterangan' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'kategori.required'       => 'Pilih kategori pengeluaran.',
            'kategori.in'             => 'Kategori tidak valid.',
            'jumlah.required'         => 'Jumlah wajib diisi.',
            'jumlah.min'              => 'Jumlah minimal Rp 1.',
            'keterangan.required'     => 'Keterangan wajib diisi.',
        ];
    }
}