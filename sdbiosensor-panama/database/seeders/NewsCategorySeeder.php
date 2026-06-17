<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        NewsCategory::insert([
            ['name' => 'Aviso',   'slug' => 'aviso',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Noticia', 'slug' => 'noticia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Evento',  'slug' => 'evento',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tabla',   'slug' => 'tabla',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
