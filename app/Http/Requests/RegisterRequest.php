<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:16'],
            'password' => ['required', 'string', 'min:8', 'max:16'],
            'email' => ['required', 'email']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
