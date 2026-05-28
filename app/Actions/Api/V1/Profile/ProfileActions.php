<?php

namespace App\Actions\Api\V1\Profile;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileActions
{
    public function show(Request $request): User
    {
        return $request->user()->load(['address', 'company.addresses']);
    }

    public function update(Request $request, array $data): User
    {
        $user = $request->user();
        $user->update($data);
        return $user->fresh(['address', 'company.addresses']);
    }
}
