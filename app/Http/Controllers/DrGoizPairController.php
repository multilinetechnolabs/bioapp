<?php

namespace App\Http\Controllers;

use App\Models\DrGoizPair;

class DrGoizPairController extends Controller
{
    public function index()
    {
        $pairs = DrGoizPair::orderBy('name')->get();
        return view('app.pages.dr_goiz_pairs.index', compact('pairs'));
    }
}
