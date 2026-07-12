<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',

            // If upload file
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // If image URL instead
            // 'image' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required.',
            'category_id.exists'   => 'Category not found.',

            'name.required'        => 'Product name is required.',
            'name.max'             => 'Product name cannot exceed 255 characters.',

            'price.required'       => 'Price is required.',
            'price.numeric'        => 'Price must be a number.',
            'price.min'            => 'Price cannot be negative.',

            'stock.required'       => 'Stock is required.',
            'stock.integer'        => 'Stock must be an integer.',
            'stock.min'            => 'Stock cannot be negative.',

            'image.image'          => 'File must be an image.',
            'image.mimes'          => 'Image must be jpg, jpeg, png or webp.',
            'image.max'            => 'Image size cannot exceed 2MB.',
        ];
    }
}