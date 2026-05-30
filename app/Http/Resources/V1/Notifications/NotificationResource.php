<?php

namespace App\Http\Resources\V1\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type,
            'status'     => $this->status,
            'is_read'    => $this->status === 'read',
            'user_id'    => $this->user_id,
            'user_id_to' => $this->user_id_to,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
