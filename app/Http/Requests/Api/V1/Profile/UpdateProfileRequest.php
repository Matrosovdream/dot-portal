<?php

namespace App\Http\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['sometimes', 'required', 'string', 'max:120'],
            'lastname'  => ['sometimes', 'required', 'string', 'max:120'],
            'phone'     => ['nullable', 'string', 'max:40'],
            'birthday'  => ['nullable', 'date'],
            'email'     => [
                'sometimes', 'required', 'string', 'email', 'max:191',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
        ];
    }
}
