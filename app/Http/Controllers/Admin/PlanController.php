<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;

class PlanController extends BaseController
{
    public function index()
    {
        return view('admin.pages.plans.index');
    }

    public function subscribers($id)
    {
        $plan = Plan::find($id);

        return view('admin.pages.plans.subscribers', compact('plan'));
    }
}
