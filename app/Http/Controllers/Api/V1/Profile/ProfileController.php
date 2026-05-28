<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Actions\Api\V1\Profile\ProfileActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Profile\UpdateProfileRequest;
use App\Http\Resources\V1\Profile\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected ProfileActions $actions) {}

    public function show(Request $request)
    {
        return new ProfileResource($this->actions->show($request));
    }

    public function update(UpdateProfileRequest $request)
    {
        return new ProfileResource($this->actions->update($request, $request->validated()));
    }
}
