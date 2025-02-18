<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SparepartRequest extends FormRequest
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
        'kode_sparepart' => 'required|string|max:6',
        'nama_sparepart' => 'required|string|max:100',
        'stok' => 'required|integer|min:0',
        'harga' => 'required|numeric|min:0',
        'kode_satuan' => 'required|exists:satuan,kode_satuan',
        'kode_brand' => 'required|exists:brand,kode_brand',
    ];
}

}
