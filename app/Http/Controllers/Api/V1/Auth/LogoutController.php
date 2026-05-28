<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\LogoutActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(protected LogoutActions $actions) {}

    public function destroy(Request $request)
    {
        $this->actions->logout($request);
        return response()->noContent();
    }
}
