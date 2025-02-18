<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Semua pengguna diizinkan
    }

    public function rules(): array
    {
        return [
            'kode'      => 'nullable|string|max:50',
            'nama'      => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'status'    => 'nullable|in:tersedia,dipinjam',
            'jumlah'    => 'nullable|integer|min:0',
            'harga'     => 'nullable|numeric|min:0',
            'tanggal'   => 'nullable|date',
        ];
    }
}
