<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        $featuredNews = NewsItem::with('category')
                                ->where('is_featured', true)
                                ->where('is_active', true)
                                ->latest('published_at')
                                ->first();

        $recentNews = NewsItem::with('category')
                              ->where('is_active', true)
                              ->latest('published_at')
                              ->take(3)
                              ->get();

        $categories = ProductCategory::where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->get();

        $featuredProducts = Product::with('category')
                                   ->where('is_active', true)
                                   ->where('is_featured', true)
                                   ->orderBy('sort_order')
                                   ->take(4)
                                   ->get();

        return view('home.index', compact(
            'featuredNews', 'recentNews', 'categories', 'featuredProducts'
        ));
    }
}
