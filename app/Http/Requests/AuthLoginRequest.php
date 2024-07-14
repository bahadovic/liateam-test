<?php

namespace App\Http\Requests;

class AuthLoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:16']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
