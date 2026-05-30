<?php

namespace App\Http\Resources\V1\Todo;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'assigned_to'  => $this->assigned_to,
            'title'        => $this->title,
            'description'  => $this->description,
            'category'     => $this->category,
            'subcategory'  => $this->subcategory,
            'status'       => $this->status,
            'priority'     => $this->priority,
            'due_date'     => $this->due_date,
            'completed_at' => $this->completed_at,
            'link'         => $this->link,
            'entity'       => $this->entity,
            'tab'          => $this->tab,
            'entity_id'    => $this->entity_id,
            'meta'         => $this->whenLoaded('meta', fn () => $this->meta->pluck('value', 'key')),
            'created_at'   => optional($this->created_at)->toIso8601String(),
        ];
    }
}
