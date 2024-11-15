<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiException extends HttpResponseException
{
    public function __construct($message = "", $code = 500, $errors = [])
    {
        $exception = [
            'message' => $message,
            'code' => $code,
        ];

        if (!empty($errors)) {
            $exception['errors'] = $errors;
        }

        parent::__construct(response()->json($exception)->setStatusCode($code));
    }
}
