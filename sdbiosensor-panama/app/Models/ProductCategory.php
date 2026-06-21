<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'color', 'short_desc', 'long_desc', 'sort_order', 'is_active',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function featuredProducts(): HasMany
    {
        return $this->hasMany(Product::class)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order');
    }
}
