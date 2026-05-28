<?php

namespace App\Actions\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class MeActions
{
    public function show(Request $request): User
    {
        return $request->user()->load('roles');
    }
}
