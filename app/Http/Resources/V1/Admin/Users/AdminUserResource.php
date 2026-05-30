<?php

namespace App\Http\Resources\V1\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = $this->getRole();

        return [
            'id'                => (int) $this->id,
            'firstname'         => $this->firstname,
            'lastname'          => $this->lastname,
            'fullname'          => $this->fullname,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'birthday'          => optional($this->birthday)->format('Y-m-d'),
            'is_active'         => (bool) $this->is_active,
            'reg_step'          => $this->reg_step,
            'role'              => $role ? [
                'id'    => (int) $role->id,
                'title' => $role->title,
                'slug'  => $role->slug,
            ] : null,
            'roles'             => $this->whenLoaded('roles', function () {
                return $this->roles->map(fn ($r) => [
                    'id'    => (int) $r->id,
                    'title' => $r->title,
                    'slug'  => $r->slug,
                ])->all();
            }),
            'email_verified_at' => optional($this->email_verified_at)->toIso8601String(),
            'created_at'        => optional($this->created_at)->toIso8601String(),
            'updated_at'        => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
