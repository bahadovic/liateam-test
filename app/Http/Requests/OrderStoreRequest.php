<?php

namespace App\Http\Requests;

class OrderStoreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required'],
            'products.*.quantity' => ['required', 'required','min:1']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
