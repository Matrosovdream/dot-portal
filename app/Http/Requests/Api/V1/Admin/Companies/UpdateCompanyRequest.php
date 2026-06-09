<?php

namespace App\Http\Requests\Api\V1\Admin\Companies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
        // user_id (owner) is immutable after creation, so it is not accepted here.
        return [
            'name'           => ['sometimes', 'required', 'string', 'max:191'],
            'phone'          => ['nullable', 'string', 'max:40'],
            'dot_number'     => ['nullable', 'string', 'max:50'],
            'mc_number'      => ['nullable', 'string', 'max:50'],
            'trucks_number'  => ['nullable', 'integer', 'min:0'],
            'drivers_number' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
