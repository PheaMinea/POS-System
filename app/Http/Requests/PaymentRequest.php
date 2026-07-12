<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,card,aba,bakong,acleda',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order is required.',
            'order_id.exists' => 'Order not found.',

            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount cannot be negative.',

            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method.',
        ];
    }
}