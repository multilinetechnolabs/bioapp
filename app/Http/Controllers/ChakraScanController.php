<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChakraScanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('subscriber')->except('info');
    }

    public function index(Request $request)
    {
        return view('app.pages.chakrascan.index', compact('request'));
    }

    public function info()
    {
        return view('app.pages.chakrascan.info');
    }
}
