<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:120'],
            'lastname'  => ['required', 'string', 'max:120'],
            'email'     => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:40'],
            'password'  => ['required', 'confirmed', Password::defaults()],
            'role'      => ['nullable', 'string', 'in:driver,company'],
        ];
    }
}
