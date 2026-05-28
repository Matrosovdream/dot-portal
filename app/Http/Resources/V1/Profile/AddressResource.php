<?php

namespace App\Http\Resources\V1\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city'     => $this->city,
            'state_id' => $this->state_id,
            'zip'      => $this->zip,
        ];
    }
}
