<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isUserActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $regStep = $user->reg_step ?? null;

        if (
            $user &&
            !$user->is_active
            ) {
            // API clients get a JSON 409 (with reg_step); web clients keep the redirect.
            if ($request->expectsJson()) {
                return response()->json(['reg_step' => $regStep ?? 1], 409);
            }
            return redirect()->route('register', [
                'step' => $regStep ?? 1,
            ]);
        }

        return $next($request);
    }
}
