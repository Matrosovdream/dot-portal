<?php

namespace App\Http\Resources\V1\Saferweb;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleCrashSaferwebListItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'report_number' => $this->report_number,
            'report_date' => $this->report_date,
            'report_state' => $this->report_state,
            'unit_vin' => $this->unit_vin,
            'dot_number' => $this->dot_number,
            'total_injuries' => (int) $this->total_injuries,
            'total_fatalities' => (int) $this->total_fatalities,
        ];
    }
}
