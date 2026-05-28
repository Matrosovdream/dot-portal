<?php

namespace App\Actions\Api\V1\Profile;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class ProfileAddressActions
{
    public function update(Request $request, array $data): UserAddress
    {
        return UserAddress::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data,
        );
    }
}
