<?php

namespace App\Http\Resources\V1\Vehicles;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'number'                 => $this->number,
            'vin'                    => $this->vin,
            'unit_type_id'           => $this->unit_type_id,
            'ownership_type_id'      => $this->ownership_type_id,
            'reg_expire_date'        => optional($this->reg_expire_date)->format('Y-m-d'),
            'inspection_expire_date' => optional($this->inspection_expire_date)->format('Y-m-d'),
            'driver_id'              => $this->driver_id,
            'company_id'             => $this->company_id,
            'is_finished'            => (bool) $this->is_finished,
            'created_at'             => optional($this->created_at)->toIso8601String(),
        ];
    }
}
