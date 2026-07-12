<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'phone.required' => 'Phone number is required.',

            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }
}