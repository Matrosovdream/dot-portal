<?php

namespace App\Http\Resources\V1\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'fullname'  => $this->fullname,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'birthday'  => optional($this->birthday)->format('Y-m-d'),
            'is_active' => (bool) $this->is_active,
            'reg_step'  => $this->reg_step,
            'email_verified' => !is_null($this->email_verified_at),
            'address'   => $this->whenLoaded('address', fn () => new AddressResource($this->address)),
            'company'   => $this->whenLoaded('company', fn () => $this->company ? new CompanyResource($this->company) : null),
        ];
    }
}
