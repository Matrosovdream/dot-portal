<?php

namespace App\Http\Requests\Api\V1\Drivers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $driver = $this->route('driver');
        $userId = is_object($driver) ? $driver->user_id : null;

        return [
            'firstname'  => ['sometimes', 'required', 'string', 'max:120'],
            'middlename' => ['nullable', 'string', 'max:120'],
            'lastname'   => ['sometimes', 'required', 'string', 'max:120'],
            'phone'      => ['nullable', 'string', 'max:40'],
            'email'      => [
                'sometimes', 'required', 'string', 'email', 'max:191',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'dob'        => ['nullable', 'date'],
            'ssn'        => ['nullable', 'string', 'max:30'],
            'hire_date'  => ['nullable', 'date'],
            'driver_type_id' => ['nullable', 'integer'],
        ];
    }
}
