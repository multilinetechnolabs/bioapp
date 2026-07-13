<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;

class CourseSubscriptionController extends BaseController
{
    public function index()
    {
        return view('admin.pages.course_subscriptions.list');
    }

    /**
     * Admin cancel course subscription endpoint (delegates to App\Http\Controllers\CourseSubscriptionController)
     */
    public function cancelSubscription(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delegate to the main controller logic
        $controller = new \App\Http\Controllers\CourseSubscriptionController();
        return $controller->cancelSubscription($request, $id);
    }
}
