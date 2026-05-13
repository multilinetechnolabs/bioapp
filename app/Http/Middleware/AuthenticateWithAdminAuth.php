<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthenticateWithAdminAuth
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
        if (Auth::user() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('message.fail', 'Access Denied. You do not have permissions to do that.');
        ;
    }
}
