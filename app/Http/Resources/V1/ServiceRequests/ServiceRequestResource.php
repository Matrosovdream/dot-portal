<?php

namespace App\Http\Resources\V1\ServiceRequests;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'service_id'     => $this->service_id,
            'status_id'      => $this->status_id,
            'order_id'       => $this->order_id,
            'is_paid'        => (bool) $this->is_paid,
            'price'          => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'service'        => $this->whenLoaded('service', fn () => [
                'id'   => $this->service->id,
                'name' => $this->service->name,
                'slug' => $this->service->slug,
            ]),
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
