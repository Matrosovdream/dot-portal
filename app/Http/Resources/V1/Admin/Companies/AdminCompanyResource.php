<?php

namespace App\Http\Resources\V1\Admin\Companies;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminCompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => (int) $this->id,
            'user_id'        => $this->user_id,
            'name'           => $this->name,
            'phone'          => $this->phone,
            'dot_number'     => $this->dot_number,
            'mc_number'      => $this->mc_number,
            'trucks_number'  => (int) $this->trucks_number,
            'drivers_number' => (int) $this->drivers_number,
            // Owning account — same {id, fullname, email} shape as the other Operations listings.
            'owner'          => $this->whenLoaded('user', fn () => $this->user ? [
                'id'       => $this->user->id,
                'fullname' => $this->user->fullname,
                'email'    => $this->user->email,
            ] : null),
            'is_active'      => $this->whenLoaded('user', fn () => (bool) ($this->user?->is_active)),
            'created_at'     => optional($this->created_at)->toIso8601String(),
        ];
    }
}
