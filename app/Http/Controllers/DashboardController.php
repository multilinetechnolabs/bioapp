<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function landing()
    {
        $locale  = $this->detectLocale();
        $seoData = config("seo.{$locale}.home", []);
        return view('home', ['locale' => $locale, 'seoPage' => 'home', 'seoData' => $seoData]);
    }

    private function detectLocale(): string
    {
        $seg = request()->segment(1);
        return in_array($seg, ['es', 'fr'], true) ? $seg : 'en';
    }

    public function index()
    {
        return view('app.pages.dashboard.index');
    }
}
