<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Validators;
use Illuminate\Foundation\Http\FormRequest;

class RemoveProductFromCartRequestValidator extends FormRequest
{
    public static function rules(): array
    {
        return [
            'user_id' => 'required|uuid',
            'product_id' => 'required|uuid',
        ];
    }
}