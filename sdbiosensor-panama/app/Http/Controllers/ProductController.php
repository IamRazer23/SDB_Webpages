<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->get();

        $activeSlug     = $request->get('categoria', $categories->first()->slug ?? 'standard-q');
        $activeCategory = ProductCategory::where('slug', $activeSlug)->firstOrFail();

        $searchTerm = $request->get('buscar');

        if ($searchTerm) {
            $products = Product::with('category')
                               ->where('is_active', true)
                               ->where(function ($q) use ($searchTerm) {
                                   $q->where('name', 'like', "%{$searchTerm}%")
                                     ->orWhere('description', 'like', "%{$searchTerm}%");
                               })
                               ->orderBy('sort_order')
                               ->get();
        } else {
            $products = $activeCategory->products()
                                       ->orderBy('sort_order')
                                       ->get();
        }

        $featuredProducts = Product::with('category')
                                   ->where('is_active', true)
                                   ->where('is_featured', true)
                                   ->orderBy('sort_order')
                                   ->take(4)
                                   ->get();

        return view('products.index', compact(
            'categories', 'activeCategory', 'products', 'featuredProducts', 'searchTerm'
        ));
    }

    public function show(string $slug)
    {
        $product = Product::with('category')
                          ->where('slug', $slug)
                          ->where('is_active', true)
                          ->firstOrFail();

        $related = Product::with('category')
                          ->where('product_category_id', $product->product_category_id)
                          ->where('id', '!=', $product->id)
                          ->where('is_active', true)
                          ->take(3)
                          ->get();

        return view('products.show', compact('product', 'related'));
    }
}
