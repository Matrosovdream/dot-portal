<?php

namespace App\Http\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name'           => ['nullable', 'string', 'max:191'],
            'phone'          => ['nullable', 'string', 'max:40'],
            'dot_number'     => ['nullable', 'string', 'max:30'],
            'mc_number'      => ['nullable', 'string', 'max:30'],
            'trucks_number'  => ['nullable', 'integer', 'min:0'],
            'drivers_number' => ['nullable', 'integer', 'min:0'],
            'business'       => ['nullable', 'array'],
            'business.address1' => ['nullable', 'string', 'max:191'],
            'business.address2' => ['nullable', 'string', 'max:191'],
            'business.city'     => ['nullable', 'string', 'max:120'],
            'business.state_id' => ['nullable', 'integer'],
            'business.zip'      => ['nullable', 'string', 'max:20'],
            'mailing'        => ['nullable', 'array'],
            'mailing.address1' => ['nullable', 'string', 'max:191'],
            'mailing.address2' => ['nullable', 'string', 'max:191'],
            'mailing.city'     => ['nullable', 'string', 'max:120'],
            'mailing.state_id' => ['nullable', 'integer'],
            'mailing.zip'      => ['nullable', 'string', 'max:20'],
        ];
    }
}
