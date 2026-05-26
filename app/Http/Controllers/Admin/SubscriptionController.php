<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;

class SubscriptionController extends BaseController
{
    public function index()
    {
        return view('admin.pages.subscriptions.list');
    }

    /**
     * Admin cancel subscription endpoint (delegates to App\Http\Controllers\SubscriptionController)
     */
    public function cancelSubscription(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delegate to the main controller logic
        $controller = new \App\Http\Controllers\SubscriptionController();
        return $controller->cancelSubscription($request, $id);
    }
}
