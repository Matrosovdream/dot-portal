<?php

namespace App\Http\Requests\Dashboard\SubManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users, change if needed
    }

    public function rules(): array
    {
        return [
            'user.firstname' => 'required|string|max:255',
            'user.lastname' => 'required|string|max:255',
            'user.phone' => 'nullable|string|max:20',
            'user.email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user.firstname.required' => 'First name is required.',
            'user.lastname.required' => 'Last name is required.',
            'user.email.required' => 'Email is required.',
            'user.email.email' => 'Email must be a valid email address.',
            'user.email.unique' => 'This email is already taken.',
            'user.phone.max' => 'Phone number cannot exceed 20 characters.',
        ];
    }

}
