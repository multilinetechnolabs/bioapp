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
        $locale = $this->detectLocale();

        if ($locale === 'en' && $redirect = $this->localeRedirect('/pricing')) {
            return $redirect;
        }

        $seoData = config("seo.{$locale}.pricing", []);
        return view('app.pages.pricing.index', ['locale' => $locale, 'seoPage' => 'pricing', 'seoData' => $seoData]);
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

    public function signup()
    {
        return redirect()->route('affiliate.index');
    }

    public function subscriptions()
    {
        return view('app.pages.users.subscriptions');
    }
}
