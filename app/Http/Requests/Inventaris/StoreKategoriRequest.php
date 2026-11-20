<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin() || $this->user()->isStaffBarang();
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255|unique:kategoris,nama',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama kategori harus diisi.',
            'nama.unique'   => 'Nama kategori sudah ada.',
        ];
    }
}
