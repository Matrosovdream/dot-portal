<?php

namespace App\Http\Resources\V1\Gateways;

use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => (int) $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'image'       => $this->image,
            'is_active'   => (bool) $this->is_active,
            'created_at'  => optional($this->created_at)->toIso8601String(),
            'updated_at'  => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
