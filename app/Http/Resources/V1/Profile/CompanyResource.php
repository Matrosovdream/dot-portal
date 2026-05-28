<?php

namespace App\Http\Resources\V1\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'phone'          => $this->phone,
            'dot_number'     => $this->dot_number,
            'mc_number'      => $this->mc_number,
            'trucks_number'  => (int) $this->trucks_number,
            'drivers_number' => (int) $this->drivers_number,
            'addresses'      => [
                'business' => optional($this->whenLoaded('businessAddress')->first())->only(['id','address1','address2','city','state_id','zip']),
                'mailing'  => optional($this->whenLoaded('mailingAddress')->first())->only(['id','address1','address2','city','state_id','zip']),
            ],
        ];
    }
}
