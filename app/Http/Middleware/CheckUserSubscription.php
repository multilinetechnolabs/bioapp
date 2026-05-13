<?php

namespace App\Http\Middleware;

use Closure;

use URL;

class CheckUserSubscription
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
        if ($request->user() && $request->user()->hasValidSubscription()) {
            return $next($request);
        }

        return redirect(\URL::route('app.pricing'))
                    ->with('message.fail', 'Your subscription has expired or is inactive. Please select a plan to continue.');
    }
}
