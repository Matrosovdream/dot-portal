<?php

namespace App\Http\Requests\Api\V1\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:120'],
            'lastname'  => ['nullable', 'string', 'max:120'],
            'email'     => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:40'],
            'birthday'  => ['nullable', 'date'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', 'string', 'exists:roles,slug'],
        ];
    }
}
