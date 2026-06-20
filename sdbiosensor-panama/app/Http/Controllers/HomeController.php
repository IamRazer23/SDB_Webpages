<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /** TTL de caché del contenido (el catálogo cambia poco). */
    private const CACHE_TTL = 600; // 10 min

    public function index()
    {
        // El contenido del home no depende de entrada del usuario → cacheable.
        // En requests cacheados no se abre conexión a la BD (latencia ~0).
        $data = Cache::remember('home:index', self::CACHE_TTL, function () {
            return [
                'featuredNews' => NewsItem::with('category')
                    ->where('is_featured', true)
                    ->where('is_active', true)
                    ->latest('published_at')
                    ->first(),
                'recentNews' => NewsItem::with('category')
                    ->where('is_active', true)
                    ->latest('published_at')
                    ->take(3)
                    ->get(),
                'categories' => ProductCategory::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(),
                'featuredProducts' => Product::with('category')
                    ->where('is_active', true)
                    ->where('is_featured', true)
                    ->orderBy('sort_order')
                    ->take(4)
                    ->get(),
            ];
        });

        return view('home.index', $data);
    }
}
