<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\NewsItem;

class MediaController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::orderByDesc('year')->get();

        $news = NewsItem::with('category')
                        ->where('is_active', true)
                        ->latest('published_at')
                        ->paginate(10);

        return view('media.index', compact('catalogs', 'news'));
    }
}
