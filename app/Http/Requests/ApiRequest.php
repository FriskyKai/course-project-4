<?php

namespace App\Http\Requests;

use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    protected function failedAuthorization()
    {
        throw new ApiException('Forbidden', 403);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiException('Unprocessable Content', 422, $validator->errors());
    }
}
