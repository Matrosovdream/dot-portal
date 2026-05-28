<?php

namespace App\Http\Resources\V1\Drivers;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverListItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $u = $this->user;
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'firstname' => $u?->firstname,
            'lastname'  => $u?->lastname,
            'fullname'  => $u?->fullname,
            'email'     => $u?->email,
            'phone'     => $u?->phone,
            'status_id' => (int) $this->status_id,
            'driver_type_id' => $this->driver_type_id,
            'hire_date' => optional($this->hire_date)->format('Y-m-d'),
            'is_finished' => (bool) $this->is_finished,
        ];
    }
}
