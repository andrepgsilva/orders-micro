<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Validators;
use Illuminate\Foundation\Http\FormRequest;

class SaveProductToCartRequestValidator extends FormRequest
{
    public static function rules(): array
    {
        return [
            'product_id' => 'required|uuid',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'user_id' => 'required|uuid',
        ];
    }
}
