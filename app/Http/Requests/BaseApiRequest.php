<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BaseApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
