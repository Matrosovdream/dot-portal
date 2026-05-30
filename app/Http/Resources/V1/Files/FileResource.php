<?php

namespace App\Http\Resources\V1\Files;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => (int) $this->id,
            'filename'    => $this->filename,
            'path'        => $this->path,
            'type'        => $this->type,
            'size'        => $this->size,
            'extension'   => $this->extension,
            'description' => $this->description,
            'disk'        => $this->disk,
            'visibility'  => $this->visibility,
            'user_id'     => $this->user_id !== null ? (int) $this->user_id : null,
            'url'         => route('api.v1.files.download', $this->id),
            'created_at'  => optional($this->created_at)->toIso8601String(),
            'updated_at'  => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
