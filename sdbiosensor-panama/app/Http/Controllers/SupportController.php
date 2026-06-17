<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('categoria', 'todos');
        $search   = $request->get('buscar');

        $query = Download::query()->orderByDesc('sort_order');

        if ($category && $category !== 'todos') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $downloads = $query->get();

        return view('support.index', compact('downloads', 'category', 'search'));
    }
}
