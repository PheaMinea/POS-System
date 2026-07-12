<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric|min:0',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists'   => 'Customer not found.',

            'items.required'       => 'Order items are required.',
            'items.array'          => 'Items must be an array.',
            'items.min'            => 'At least one item is required.',

            'items.*.product_id.required' => 'Product is required.',
            'items.*.product_id.exists'   => 'Product not found.',

            'items.*.quantity.required'   => 'Quantity is required.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',

            'items.*.price.required'      => 'Price is required.',
        ];
    }
}