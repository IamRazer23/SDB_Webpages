<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $standardQ = ProductCategory::where('slug', 'standard-q')->first()->id;
        $bgms = ProductCategory::where('slug', 'bgms')->first()->id;

        Product::insert([
            [
                'product_category_id' => $standardQ,
                'name' => 'COVID-19 Ag Home Test',
                'slug' => 'covid-19-ag-home-test',
                'description' => 'Prueba de detección rápida de antígeno SARS-CoV-2 para uso en el hogar.',
                'image_path' => null,
                'certifications' => json_encode(['CE', 'KMFDS']),
                'is_featured' => true,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'product_category_id' => $standardQ,
                'name' => 'i-Q COVID/Flu Ag Combo',
                'slug' => 'iq-covid-flu-ag-combo',
                'description' => 'Prueba combo para detección simultánea de COVID-19 e influenza.',
                'image_path' => null,
                'certifications' => json_encode(['CE']),
                'is_featured' => true,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'product_category_id' => $standardQ,
                'name' => 'COVID/Flu Ag Combo',
                'slug' => 'covid-flu-ag-combo',
                'description' => 'Diagnóstico diferencial rápido entre COVID-19 e influenza.',
                'image_path' => null,
                'certifications' => json_encode(['CE', 'KMFDS']),
                'is_featured' => true,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'product_category_id' => $standardQ,
                'name' => 'Malaria P.f Ag',
                'slug' => 'malaria-pf-ag',
                'description' => 'Prueba rápida para detección de Plasmodium falciparum.',
                'image_path' => null,
                'certifications' => json_encode(['CE', 'WHO_PQ', 'TGA']),
                'is_featured' => true,
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'product_category_id' => $standardQ,
                'name' => 'SARS-CoV-2 Ag FIA',
                'slug' => 'sars-cov-2-ag-fia',
                'description' => 'Prueba cuantitativa de antígeno SARS-CoV-2 por inmunoensayo de fluorescencia.',
                // Ejemplo del flujo de medios (Opción A): archivo en
                // public/media/productos/ y ruta relativa en image_path.
                'image_path' => 'productos/sars-cov-2-ag-fia.jpg',
                'certifications' => json_encode(['CE', 'KMFDS']),
                'is_featured' => false,
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'product_category_id' => $bgms,
                'name' => 'STANDARD PRIME BG',
                'slug' => 'standard-prime-bg',
                'description' => 'Monitor de glucosa en sangre de alta precisión para uso profesional.',
                'image_path' => null,
                'certifications' => json_encode(['CE', 'KMFDS']),
                'is_featured' => false,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
