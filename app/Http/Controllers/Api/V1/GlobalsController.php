<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\References\GlobalsActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GlobalsController extends Controller
{
    public function __construct(protected GlobalsActions $actions) {}

    public function show(): JsonResponse
    {
        return response()->json(['data' => $this->actions->show()]);
    }
}
