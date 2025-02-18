<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SparepartEditRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    return [
        'kode_sparepart' => 'nullable|string|max:6',
        'nama_sparepart' => 'nullable|string|max:100',
        'stok' => 'nullable|integer|min:0',
        'harga' => 'nullable|numeric|min:0',
        'kode_satuan' => 'nullable|exists:satuan,kode_satuan',
        'kode_brand' => 'nullable|exists:brand,kode_brand',
    ];
}

}
