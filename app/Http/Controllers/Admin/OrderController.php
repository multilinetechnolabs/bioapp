<?php

namespace App\Http\Controllers\Admin;

class OrderController extends BaseController
{
    public function index()
    {
        return view('admin.pages.orders.index');
    }
}
