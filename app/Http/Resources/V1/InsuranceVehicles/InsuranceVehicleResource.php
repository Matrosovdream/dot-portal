<?php

namespace App\Http\Resources\V1\InsuranceVehicles;

use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceVehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'number'     => $this->number,
            'start_date' => optional($this->start_date)->format('Y-m-d'),
            'end_date'   => optional($this->end_date)->format('Y-m-d'),
            'file_id'    => $this->file_id,
            'company_id' => $this->company_id,
            'user_id'    => $this->user_id,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
