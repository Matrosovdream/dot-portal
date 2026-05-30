<?php

namespace App\Http\Requests\Api\V1\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'firstname' => ['sometimes', 'required', 'string', 'max:120'],
            'lastname'  => ['nullable', 'string', 'max:120'],
            'email'     => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($this->route('user')->id),
            ],
            'phone'     => ['nullable', 'string', 'max:40'],
            'birthday'  => ['nullable', 'date'],
            'password'  => ['sometimes', 'nullable', 'string', 'min:8'],
            'role'      => ['sometimes', 'required', 'string', 'exists:roles,slug'],
        ];
    }
}
