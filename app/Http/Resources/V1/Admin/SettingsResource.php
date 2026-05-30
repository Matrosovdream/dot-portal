<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * The resource is a flat associative map of canonical setting keys to
     * their resolved values, e.g. ['sitename' => 'Acme', 'email' => '', ...].
     * Returning it as-is wraps the map in {data:{...}} per the default
     * JsonResource behaviour.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settings = [];

        foreach ((array) $this->resource as $key => $value) {
            $settings[$key] = (string) ($value ?? '');
        }

        return $settings;
    }
}
