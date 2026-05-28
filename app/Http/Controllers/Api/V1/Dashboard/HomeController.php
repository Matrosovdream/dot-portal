<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Actions\Api\V1\Dashboard\HomeActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected HomeActions $actions) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->actions->show($request->user())]);
    }
}
