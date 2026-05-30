<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\GatewayActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Gateways\GatewayResource;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function __construct(protected GatewayActions $actions) {}

    public function index(Request $request)
    {
        return GatewayResource::collection($this->actions->index($request));
    }
}
