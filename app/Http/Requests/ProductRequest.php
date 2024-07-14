<?php

namespace App\Http\Requests;


class ProductRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'inventory' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
