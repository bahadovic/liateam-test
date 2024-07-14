<?php

namespace App\Http\Requests;

class AuthLoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:5', 'max:16'],
            'password' => ['required', 'string', 'min:8', 'max:16']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
