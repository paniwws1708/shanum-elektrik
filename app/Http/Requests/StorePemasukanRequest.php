<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemasukanRequest extends FormRequest
{
    // Pengecekan role sudah dilakukan di route, jadi di sini cukup true.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'jumlah' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'keterangan' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal Rp 1.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ];
    }
}
