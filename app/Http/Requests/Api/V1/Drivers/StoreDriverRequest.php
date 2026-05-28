<?php

namespace App\Http\Requests\Api\V1\Drivers;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'firstname'  => ['required', 'string', 'max:120'],
            'middlename' => ['nullable', 'string', 'max:120'],
            'lastname'   => ['required', 'string', 'max:120'],
            'phone'      => ['required', 'string', 'max:40'],
            'email'      => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'password'   => ['nullable', 'string', 'min:8'],
            'dob'        => ['nullable', 'date'],
            'ssn'        => ['nullable', 'string', 'max:30'],
            'hire_date'  => ['nullable', 'date'],
            'driver_type_id' => ['nullable', 'integer'],
        ];
    }
}
