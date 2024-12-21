<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//          'user_id' => 'required|integer|exists:users,id',
            'username' => 'required|string|exists:users,username',
            'file_id' => 'required|integer|exists:files,id',
        ];
    }
}
