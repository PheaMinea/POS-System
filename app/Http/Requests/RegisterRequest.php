<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'password' => 'required|string|min:6|max:255|confirmed',

            'role' => 'nullable|in:admin,cashier,customer',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'Email already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'role.in' => 'Role must be admin, cashier, or customer.',

            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }
}