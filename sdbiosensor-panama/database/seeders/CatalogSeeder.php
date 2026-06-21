<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        Catalog::insert([
            [
                'title' => 'Catálogo Completo de Productos 2024',
                'cover_image_path' => null,
                'file_path' => null,
                'year' => 2024,
                'sort_order' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Catálogo Completo de Productos 2023',
                'cover_image_path' => null,
                'file_path' => null,
                'year' => 2023,
                'sort_order' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Catálogo Completo de Productos 2022',
                'cover_image_path' => null,
                'file_path' => null,
                'year' => 2022,
                'sort_order' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Catálogo Completo de Productos 2021',
                'cover_image_path' => null,
                'file_path' => null,
                'year' => 2021,
                'sort_order' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
