<?php

namespace App\Http\Resources\V1\References;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Generic resource shape for reference items.
 * Picks id + a label field (one of: name, title, code, type) + meta.
 */
class ReferenceResource extends JsonResource
{
    public function toArray($request): array
    {
        $attrs = $this->resource->getAttributes();

        return [
            'id'    => $this->id,
            'name'  => $attrs['name'] ?? $attrs['title'] ?? $attrs['type'] ?? $attrs['code'] ?? null,
            'slug'  => $attrs['slug'] ?? $attrs['code'] ?? null,
            'meta'  => array_diff_key($attrs, array_flip(['id', 'name', 'title', 'slug', 'code', 'created_at', 'updated_at'])),
        ];
    }
}
