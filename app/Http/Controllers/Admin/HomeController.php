<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\AdminController as ApiAdminController;

class HomeController extends BaseController
{
    public function index()
    {
        return view('admin.pages.index');
    }

    public function email()
    {
        return view('admin.pages.email');
    }

    public function sendEmail(Request $request, ApiAdminController $adminController)
    {
        return $adminController->mail($request);
    }
}
