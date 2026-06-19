<?php

namespace App\Http\Controllers;

use App\Models\DrGoizPair;

class DrGoizPairController extends Controller
{
    public function index()
    {
        $locale  = $this->detectLocale();
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
}
