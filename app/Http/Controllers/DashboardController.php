<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function landing()
    {
        $locale = $this->detectLocale();

        if ($locale === 'en' && $redirect = $this->localeRedirect('/home')) {
            return $redirect;
        }

        $seoData = config("seo.{$locale}.home", []);
        return view('home', ['locale' => $locale, 'seoPage' => 'home', 'seoData' => $seoData]);
    }

    private function detectLocale(): string
    {
        $seg = request()->segment(1);
        return in_array($seg, ['es', 'fr'], true) ? $seg : 'en';
    }

    private function localeRedirect(string $enPath)
    {
        $gt    = $_COOKIE['googtrans'] ?? '';
        $parts = array_values(array_filter(explode('/', $gt)));
        $lang  = end($parts);
        if (!in_array($lang, ['es', 'fr'], true)) return null;
        return redirect("/{$lang}{$enPath}");
    }

    public function index()
    {
        return view('app.pages.dashboard.index');
    }
}
