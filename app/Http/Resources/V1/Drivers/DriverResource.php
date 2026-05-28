<?php

namespace App\Http\Resources\V1\Drivers;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray($request): array
    {
        $u = $this->user;
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'company_id' => $this->company_id,
            'firstname' => $u?->firstname,
            'lastname'  => $u?->lastname,
            'fullname'  => $u?->fullname,
            'email'     => $u?->email,
            'phone'     => $u?->phone,
            'dob'       => optional($this->dob)->format('Y-m-d'),
            'ssn'       => $this->ssn,
            'hire_date' => optional($this->hire_date)->format('Y-m-d'),
            'driver_type_id' => $this->driver_type_id,
            'status_id' => (int) $this->status_id,
            'is_finished' => (bool) $this->is_finished,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
