<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdminOrManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdminOrManager()) {
            return response()->json(['message' => "You don't have permission."], 422);
        }

        return $next($request);
    }
}
