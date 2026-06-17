<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        ProductCategory::insert([
            [
                'name'       => 'STANDARD Q',
                'slug'       => 'standard-q',
                'color'      => '#cc0066',
                'short_desc' => 'Diagnóstico Rápido STANDARD Q — alta sensibilidad y especificidad.',
                'long_desc'  => 'STANDARD Q ofrece productos de diagnóstico rápido con alta sensibilidad y especificidad mediante control de calidad desde el desarrollo de materia prima hasta la producción.',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'STANDARD F',
                'slug'       => 'standard-f',
                'color'      => '#44aa00',
                'short_desc' => 'STANDARD F — diagnóstico rápido de influenza.',
                'long_desc'  => 'STANDARD F es la línea de diagnóstico rápido para enfermedades respiratorias, con resultados rápidos y confiables.',
                'sort_order' => 2,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'STANDARD E',
                'slug'       => 'standard-e',
                'color'      => '#003366',
                'short_desc' => 'STANDARD E — pruebas de electroquimioluminiscencia.',
                'long_desc'  => null,
                'sort_order' => 3,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'STANDARD M',
                'slug'       => 'standard-m',
                'color'      => '#0099cc',
                'short_desc' => 'STANDARD M — monitores de glucosa en sangre.',
                'long_desc'  => null,
                'sort_order' => 4,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'STANDARD i',
                'slug'       => 'standard-i',
                'color'      => '#00aaaa',
                'short_desc' => 'STANDARD i — analizadores inmunológicos.',
                'long_desc'  => null,
                'sort_order' => 5,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'STANDARD C',
                'slug'       => 'standard-c',
                'color'      => '#1a3a6e',
                'short_desc' => 'STANDARD C — pruebas de química clínica.',
                'long_desc'  => null,
                'sort_order' => 6,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'BGMS',
                'slug'       => 'bgms',
                'color'      => '#222244',
                'short_desc' => 'BGMS — sistemas de monitoreo de glucosa en sangre.',
                'long_desc'  => null,
                'sort_order' => 7,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name'       => 'Etc',
                'slug'       => 'etc',
                'color'      => '#6600cc',
                'short_desc' => 'Otros productos SD Biosensor.',
                'long_desc'  => null,
                'sort_order' => 8,
                'is_active'  => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
