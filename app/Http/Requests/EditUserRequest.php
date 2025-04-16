<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($this->id),
        ], 
            'telepon' => 'required|min:10',
            'role' => 'required|string|in:admin,pegawai,supervisor,user', 
            'password' => 'required|min:8',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'saldo' => 'nullable',
        ];
    }

    public function prepareForValidation()
            {
                // Supaya $this->id bisa dipakai di rules
                $this->merge([
                    'id' => $this->route('basic')->id ?? $this->id,
                ]);
            }
}
