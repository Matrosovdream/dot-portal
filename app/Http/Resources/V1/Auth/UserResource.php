<?php

namespace App\Http\Resources\V1\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'firstname'  => $this->firstname,
            'lastname'   => $this->lastname,
            'fullname'   => $this->fullname,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'birthday'   => optional($this->birthday)->format('Y-m-d'),
            'is_active'  => (bool) $this->is_active,
            'reg_step'   => $this->reg_step,
            'email_verified' => !is_null($this->email_verified_at),
            'roles'      => $this->whenLoaded('roles', fn () => $this->roles->pluck('slug')->all()),
            'flags'      => [
                'is_admin'   => $this->isAdmin(),
                'is_manager' => $this->isManager(),
                'is_company' => $this->isCompany(),
                'is_driver'  => $this->isDriver(),
            ],
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
