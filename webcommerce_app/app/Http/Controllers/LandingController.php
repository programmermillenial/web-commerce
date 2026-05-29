<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuImage;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    function index()
    {
        $categories = MenuCategory::with([
            'menus' => function ($q) {
                $q->where('is_active', 1)
                    ->with('activePrice');
            }
        ])
            ->where('is_active', 1)
            ->get();

        $images = MenuImage::where('is_primary', 1)->get();

        return view('web.index', compact('categories', 'images'));
    }
}
