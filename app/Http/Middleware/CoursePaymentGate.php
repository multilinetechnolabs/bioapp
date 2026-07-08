<?php

namespace App\Http\Middleware;

use Closure;

class CoursePaymentGate
{
    const SESSION_KEY = 'course_preview.paid';

    public function handle($request, Closure $next)
    {
        if (session(self::SESSION_KEY)) {
            return $next($request);
        }

        return response()->view('app.pages.course.paywall', [], 200);
    }
}
