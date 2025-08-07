<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Actions\Auth\RegisterUserActions;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{

    protected $actions;

    public function __construct()
    {
        $this->actions = app(RegisterUserActions::class);
    }

    public function create(): View
    {
        return view('auth.register', 
            $this->actions->index() 
        );
    }

    public function registerRemove() {

        $res = $this->actions->registerRemove();

        if( $res ) {
            return redirect()->route('register');
        } else {
            return redirect()->back();
        }

    }

    public function store(Request $request): RedirectResponse
    {

        $res = $this->actions->store($request);

        if( isset( $res['result'] ) ) {
            return redirect($res['next_page']);
        }

        return redirect()->back()->withErrors(['error' => $res['message']]);

    }

    public function retrieveUsdot(Request $request): JsonResponse
    {
        return response()->json(
            $this->actions->retrieveUsdot($request)
        );
    }

}
