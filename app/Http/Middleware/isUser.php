<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isUser
{

    public function handle(Request $request, Closure $next): Response
    {
        if ( !auth()->user()->isUser() ) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
