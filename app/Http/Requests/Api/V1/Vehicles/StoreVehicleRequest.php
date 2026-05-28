<?php

namespace App\Http\Requests\Api\V1\Vehicles;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'number'                 => ['required', 'string', 'max:60'],
            'vin'                    => ['nullable', 'string', 'max:60'],
            'unit_type_id'           => ['nullable', 'integer'],
            'ownership_type_id'      => ['nullable', 'integer'],
            'reg_expire_date'        => ['nullable', 'date'],
            'inspection_expire_date' => ['nullable', 'date'],
            'driver_id'              => ['nullable', 'integer'],
        ];
    }
}
