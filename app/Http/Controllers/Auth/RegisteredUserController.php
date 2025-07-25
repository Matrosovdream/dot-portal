<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Actions\Auth\RegisterUserActions;

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

    public function store(Request $request): RedirectResponse
    {

        $res = $this->actions->store($request);

        return redirect(route('dashboard.home', absolute: false));
    }

    public function retrieveUsdot(Request $request)
    {
        return response()->json(
            $this->actions->retrieveUsdot($request)
        );
    }

}
