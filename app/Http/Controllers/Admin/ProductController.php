<?php

namespace App\Http\Controllers\Admin;

class ProductController extends BaseController
{
    public function index()
    {
        return view('admin.pages.products.index');
    }
}
