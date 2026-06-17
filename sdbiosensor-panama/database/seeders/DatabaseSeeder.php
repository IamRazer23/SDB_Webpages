<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            CatalogSeeder::class,
            DownloadSeeder::class,
        ]);
    }
}
