<?php

namespace App\Http\Resources\V1\Saferweb;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleInspectionSaferwebListItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'report_number' => $this->report_number,
            'report_date' => $this->report_date,
            'inspection_level' => $this->inspection_level,
            'report_state' => $this->report_state,
            'unit_vin' => $this->unit_vin,
            'dot_number' => $this->dot_number,
        ];
    }
}
