<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnforceSingleSession
{
    /**
     * If this authenticated user's session ID no longer matches the one recorded
     * as their allowed session, a newer login elsewhere has taken over — log this
     * one out server-side.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();

            if (!empty($user->current_session_id) && $user->current_session_id !== $currentSessionId) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('message.fail', 'You have been logged out because your account was accessed from another device.');
            }
        }

        return $next($request);
    }

    /**
     * Record the session ID AFTER the response is fully built, not on the Login
     * event. Laravel's login flow regenerates the session ID more than once
     * (once inside the guard's login(), again in AuthenticatesUsers::
     * sendLoginResponse()) — capturing it at the Login event grabs a value that's
     * already stale by the time the final session cookie reaches the browser,
     * which locked every user out on their very next request. terminate() runs
     * after all of that has settled, so the ID captured here is the real final one.
     */
    public function terminate($request, $response)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $currentSessionId = $request->session()->getId();

        if ($user->current_session_id !== $currentSessionId) {
            $user->forceFill(['current_session_id' => $currentSessionId])->save();
        }
    }
}
