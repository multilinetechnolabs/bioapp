<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index()
    {
        return view('admin.pages.users.index');
    }

    public function subscriptions(Request $request, $id)
    {
        $user = User::find($id);

        return view('admin.pages.subscriptions.index', compact('user'));
    }
}
