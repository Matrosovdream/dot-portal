<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\MeActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __construct(protected MeActions $actions) {}

    public function show(Request $request)
    {
        return new UserResource($this->actions->show($request));
    }
}
