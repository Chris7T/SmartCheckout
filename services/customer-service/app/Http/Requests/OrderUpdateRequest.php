<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_id' => ['nullable', 'integer', 'in:1,2,3,4'],
            'payment_status_id' => ['nullable', 'integer', 'in:1,2,3,4'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'liquid_value' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'status_id.in' => 'Invalid status.',
            'payment_status_id.in' => 'Invalid payment status.',
            'value.min' => 'The value must be at least 0.',
            'liquid_value.min' => 'The liquid value must be at least 0.',
        ];
    }
}
