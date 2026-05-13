<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function landing()
    {
        return view('home');
    }

    public function index()
    {
        return view('app.pages.dashboard.index');
    }
}
