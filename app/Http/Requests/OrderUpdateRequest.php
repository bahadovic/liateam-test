<?php

namespace App\Http\Requests;

class OrderUpdateRequest extends BaseFormRequest
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
