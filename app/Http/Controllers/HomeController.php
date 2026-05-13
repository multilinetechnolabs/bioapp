<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function landing_page()
    {
        return view('landing_page');
    }

    public function orders()
    {
        return view('app.pages.users.orders');
    }

    public function payments()
    {
        return view('app.pages.users.payments');
    }

    public function preloader()
    {
        return view('preloader');
    }

    public function pricing()
    {
        return view('app.pages.pricing.index');
    }

    public function signup()
    {
        return redirect()->route('affiliate.index');
    }

    public function subscriptions()
    {
        return view('app.pages.users.subscriptions');
    }
}
