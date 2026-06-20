<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices de rendimiento (agnóstico de motor: MySQL y PostgreSQL/Supabase).
 *
 * Cubre los filtros reales de los controladores:
 *  - ProductController: where(is_active) + orderBy(sort_order); category + is_active.
 *  - HomeController:    is_active + latest(published_at).
 *  - SupportController: where(category) + orderByDesc(sort_order).
 *
 * La búsqueda LIKE/ILIKE se acelera aparte con índices GIN pg_trgm en Supabase
 * (ver supabase/migrations/0001_supabase_layer.sql).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'products_active_sort_idx');
            $table->index(['product_category_id', 'is_active'], 'products_category_active_idx');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->index(['is_active', 'published_at'], 'news_active_published_idx');
        });

        Schema::table('downloads', function (Blueprint $table) {
            $table->index(['category', 'sort_order'], 'downloads_category_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_sort_idx');
            $table->dropIndex('products_category_active_idx');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->dropIndex('news_active_published_idx');
        });

        Schema::table('downloads', function (Blueprint $table) {
            $table->dropIndex('downloads_category_sort_idx');
        });
    }
};
