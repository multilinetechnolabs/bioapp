<?php

namespace App\Http\Controllers;

use App\Models\DrGoizPair;

class DrGoizPairController extends Controller
{
    public function index()
    {
        $locale = $this->detectLocale();

        if ($locale === 'en' && $redirect = $this->localeRedirect('/free-protocol-pairs')) {
            return $redirect;
        }

        $seoData = config("seo.{$locale}.free-protocol-pairs", []);
        $pairs   = DrGoizPair::orderBy('name')->get();
        return view('app.pages.dr_goiz_pairs.index', compact('pairs') + [
            'locale'  => $locale,
            'seoPage' => 'free-protocol-pairs',
            'seoData' => $seoData,
        ]);
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
}
