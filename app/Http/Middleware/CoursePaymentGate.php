<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\CoursePurchase;
use Closure;

class CoursePaymentGate
{
    public function handle($request, Closure $next)
    {
        $course = Course::where('is_active', true)->first();

        if (!$course) {
            return response()->view('app.pages.course.unavailable', [], 200);
        }

        if (CoursePurchase::userHasAccess($request->user()->id ?? null, $course->id)) {
            return $next($request);
        }

        $expired = CoursePurchase::where('user_id', $request->user()->id ?? null)
            ->where('course_id', $course->id)
            ->exists();

        return response()->view('app.pages.course.paywall', ['expired' => $expired, 'course' => $course], 200);
    }
}
