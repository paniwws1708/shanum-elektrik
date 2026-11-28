<?php

namespace App\Http\Requests\Inventaris;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
        $supplierId = $this->route('supplier')?->id;

        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'nama')->ignore($supplierId),
            ],
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama supplier harus diisi.',
            'nama.unique' => 'Nama supplier sudah terdaftar.',
        ];
    }
}
