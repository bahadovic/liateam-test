<?php

namespace App\Http\Requests;

class OrderStoreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'products' => ['required'],
            'total_price' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
