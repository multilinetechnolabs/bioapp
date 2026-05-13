<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DrGoizPairController extends BaseController
{
    public function index()
    {
        return view('admin.pages.dr_goiz_pairs.index');
    }
}
