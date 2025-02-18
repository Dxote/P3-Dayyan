<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'telepon' => 'required|min:10',
            'level' => 'required|string|in:admin,petugas,pengguna', 
            'password' => 'required|min:8',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192' // Validasi untuk foto
        ];
    }
}
