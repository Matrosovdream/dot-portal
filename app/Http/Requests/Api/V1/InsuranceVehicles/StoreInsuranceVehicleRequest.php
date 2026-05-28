<?php

namespace App\Http\Requests\Api\V1\InsuranceVehicles;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:191'],
            'number'     => ['nullable', 'string', 'max:60'],
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'file_id'    => ['nullable', 'integer'],
        ];
    }
}
