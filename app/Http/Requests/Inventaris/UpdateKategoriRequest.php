<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin() || $this->user()->isStaffBarang();
    }

    public function rules()
    {
        $kategoriId = $this->route('kategori')?->id;
        return [
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategoriId,
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
