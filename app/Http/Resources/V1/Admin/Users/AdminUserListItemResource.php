<?php

namespace App\Http\Resources\V1\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserListItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = $this->getRole();

        return [
            'id'         => (int) $this->id,
            'firstname'  => $this->firstname,
            'lastname'   => $this->lastname,
            'fullname'   => $this->fullname,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'is_active'  => (bool) $this->is_active,
            'role'       => $role ? $role->slug : null,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
