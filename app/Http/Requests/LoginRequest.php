<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Email format is invalid.',

            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
        ];
    }
}