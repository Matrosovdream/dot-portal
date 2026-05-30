<?php

namespace App\Http\Resources\V1\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => (int) $this->id,
            'order_id'         => (int) $this->order_id,
            'item_name'        => $this->item_name,
            'item_description' => $this->item_description,
            'entity'           => $this->entity,
            'entity_id'        => $this->entity_id !== null ? (int) $this->entity_id : null,
            'quantity'         => (int) $this->quantity,
            'unit_price'       => (float) $this->unit_price,
            'total_price'      => (float) $this->total_price,
        ];
    }
}
