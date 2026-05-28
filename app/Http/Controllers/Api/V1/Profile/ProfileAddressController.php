<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Actions\Api\V1\Profile\ProfileAddressActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Profile\UpdateAddressRequest;
use App\Http\Resources\V1\Profile\AddressResource;

class ProfileAddressController extends Controller
{
    public function __construct(protected ProfileAddressActions $actions) {}

    public function update(UpdateAddressRequest $request)
    {
        return new AddressResource($this->actions->update($request, $request->validated()));
    }
}
