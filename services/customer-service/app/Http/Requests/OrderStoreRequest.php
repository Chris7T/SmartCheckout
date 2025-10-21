<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer'],
            'value' => ['required', 'numeric', 'min:0'],
            'liquid_value' => ['required', 'numeric', 'min:0'],
            'status_id' => ['nullable', 'integer', 'in:1,2,3,4'],
            'payment_status_id' => ['nullable', 'integer', 'in:1,2,3,4'],
            'carts' => ['required', 'array', 'min:1'],
            'carts.*.product_id' => ['required', 'integer'],
            'carts.*.value' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'The customer ID is required.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'value.required' => 'The order value is required.',
            'liquid_value.required' => 'The liquid value is required.',
            'carts.required' => 'The order must have at least one cart item.',
            'carts.*.product_id.required' => 'Each cart item must have a product ID.',
            'carts.*.value.required' => 'Each cart item must have a value.',
        ];
    }
}
