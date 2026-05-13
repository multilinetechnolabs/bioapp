<?php

namespace App\Http\Controllers\Admin;

class ModelLabelController extends BaseController
{
    public function index()
    {
        return view('admin.pages.model_labels.index');
    }

    public function bodyscan()
    {
        return view('admin.pages.model_labels.bodyscan');
    }

    public function chakrascan()
    {
        return view('admin.pages.model_labels.chakrascan');
    }
}
