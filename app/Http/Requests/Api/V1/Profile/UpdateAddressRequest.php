<?php

namespace App\Http\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'address1' => ['nullable', 'string', 'max:191'],
            'address2' => ['nullable', 'string', 'max:191'],
            'city'     => ['nullable', 'string', 'max:120'],
            'state_id' => ['nullable', 'integer'],
            'zip'      => ['required', 'string', 'max:20'],
        ];
    }
}
