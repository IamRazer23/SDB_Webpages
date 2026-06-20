<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /** TTL de caché del catálogo (cambia poco). */
    private const CACHE_TTL = 600; // 10 min

    public function index(Request $request)
    {
        // Listas sin entrada de usuario → cacheables.
        $categories = Cache::remember('products:categories', self::CACHE_TTL, fn () =>
            ProductCategory::where('is_active', true)->orderBy('sort_order')->get()
        );

        $activeSlug     = $request->get('categoria', $categories->first()->slug ?? 'standard-q');
        $activeCategory = Cache::remember("category:slug:{$activeSlug}", self::CACHE_TTL, fn () =>
            ProductCategory::where('slug', $activeSlug)->first()
        );
        abort_if($activeCategory === null, 404);

        $searchTerm = $request->get('buscar');

        if ($searchTerm) {
            // La búsqueda depende del término → no se cachea (además va con rate limit).
            $products = Product::with('category')
                               ->where('is_active', true)
                               ->where(function ($q) use ($searchTerm) {
                                   $q->where('name', 'ilike', "%{$searchTerm}%")
                                     ->orWhere('description', 'ilike', "%{$searchTerm}%");
                               })
                               ->orderBy('sort_order')
                               ->get();
        } else {
            $products = Cache::remember("products:cat:{$activeCategory->id}", self::CACHE_TTL, fn () =>
                $activeCategory->products()->orderBy('sort_order')->get()
            );
        }

        $featuredProducts = Cache::remember('products:featured', self::CACHE_TTL, fn () =>
            Product::with('category')
                   ->where('is_active', true)
                   ->where('is_featured', true)
                   ->orderBy('sort_order')
                   ->take(4)
                   ->get()
        );

        return view('products.index', compact(
            'categories', 'activeCategory', 'products', 'featuredProducts', 'searchTerm'
        ));
    }

    public function show(string $slug)
    {
        $product = Cache::remember("product:slug:{$slug}", self::CACHE_TTL, fn () =>
            Product::with('category')
                   ->where('slug', $slug)
                   ->where('is_active', true)
                   ->first()
        );
        abort_if($product === null, 404);

        $related = Cache::remember("product:related:{$product->id}", self::CACHE_TTL, fn () =>
            Product::with('category')
                   ->where('product_category_id', $product->product_category_id)
                   ->where('id', '!=', $product->id)
                   ->where('is_active', true)
                   ->take(3)
                   ->get()
        );

        return view('products.show', compact('product', 'related'));
    }
}
