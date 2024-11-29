<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surname' => 'string|min:2|max:32',
            'name' => 'string|min:2|max:32',
            'username' => 'string|min:6|max:64',
            'password' => 'string|min:6|max:255',
            'email' => 'string|email|max:255|unique:users',
            'phone' => 'string|max:16|unique:users',
        ];
    }
}
