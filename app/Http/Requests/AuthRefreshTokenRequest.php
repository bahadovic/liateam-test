<?php

namespace App\Http\Requests;


class AuthRefreshTokenRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
