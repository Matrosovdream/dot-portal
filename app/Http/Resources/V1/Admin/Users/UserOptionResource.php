<?php

namespace App\Http\Resources\V1\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Minimal user shape for the admin/manager owner-filter picker:
 * just enough to render "Fullname (email)" and bind the id.
 */
class UserOptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => (int) $this->id,
            'fullname' => $this->fullname,
            'email'    => $this->email,
        ];
    }
}
