<?php

namespace App\Http\Resources\V1\Saferweb;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleInspectionSaferwebResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'vehicle_id' => $this->vehicle_id !== null ? (int) $this->vehicle_id : null,
            'unit_vin' => $this->unit_vin,
            'company_id' => $this->company_id !== null ? (int) $this->company_id : null,
            'dot_number' => $this->dot_number,
            'unique_id' => $this->unique_id,
            'report_number' => $this->report_number,
            'report_sequence_number' => $this->report_sequence_number,
            'report_date' => $this->report_date,
            'inspection_level' => $this->inspection_level,
            'report_state' => $this->report_state,
            'report_state_id' => $this->report_state_id !== null ? (int) $this->report_state_id : null,
            'api_data' => $this->api_data,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
