<?php

namespace App\Http\Controllers\Admin;

class SubscriptionController extends BaseController
{
    public function index()
    {
        return view('admin.pages.subscriptions.list');
    }
}
