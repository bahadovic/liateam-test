<?php

namespace App\Http\Requests;


class ProductUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric'],
            'inventory' => ['sometimes', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
