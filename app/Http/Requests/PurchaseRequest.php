<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
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
            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.exists'   => 'Supplier not found.',

            'total_price.required' => 'Total price is required.',
            'total_price.numeric'  => 'Total price must be numeric.',

            'items.required'       => 'Purchase items are required.',
            'items.array'          => 'Items must be an array.',
            'items.min'            => 'At least one item is required.',

            'items.*.product_id.required' => 'Product is required.',
            'items.*.product_id.exists'   => 'Product not found.',

            'items.*.quantity.required'   => 'Quantity is required.',
            'items.*.quantity.integer'    => 'Quantity must be an integer.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',

            'items.*.price.required'      => 'Price is required.',
            'items.*.price.numeric'       => 'Price must be numeric.',
            'items.*.price.min'           => 'Price cannot be negative.',
        ];
    }
}