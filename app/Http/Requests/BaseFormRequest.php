<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $data = array();

        foreach ($validator->errors()->messages() as $name => $error) {
            $data[$name] = implode(', ', $error);
        }

        $response = responseFormatter()->entity($data);
        throw new HttpResponseException(response()->json($response['data'], $response['httpStatusCode']));
    }
}
