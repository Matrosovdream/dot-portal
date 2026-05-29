<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class hasRole
{

    public function handle(Request $request, Closure $next): Response
    {

        $roles = array_slice(func_get_args(), 2);

        if ( !auth()->user()->hasRole($roles) ) {
            // API clients get a JSON 403; web clients keep the redirect.
            if ($request->expectsJson()) {
                abort(403, 'Forbidden.');
            }
            return redirect()->route('web.index');
        }

        return $next($request);
    }
}
