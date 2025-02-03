<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;



class IndexController extends Controller {

    public function index( Request $request )
    {
        return redirect()->route('dashboard.home');
    }

    public function lg( Request $request )
    {
        if( ! $request->user ) {
            return redirect()->route('web.index');
        }
        
        $user = User::find( $request->user );
        auth()->login($user);
        return redirect()->route('dashboard.home');
    }

}


