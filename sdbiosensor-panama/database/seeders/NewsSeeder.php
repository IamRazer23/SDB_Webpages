<?php

namespace Database\Seeders;

use App\Models\NewsItem;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        NewsItem::insert([
            [
                'news_category_id' => 1,
                'title'            => 'Inversión de US$21M impulsa diagnóstico molecular para COVID-19 y otras enfermedades',
                'summary'          => 'SD Biosensor anuncia inversión significativa para expandir su capacidad de diagnóstico molecular.',
                'content'          => null,
                'image_path'       => null,
                'is_featured'      => false,
                'is_active'        => true,
                'published_at'     => '2026-06-01',
                'created_at'       => now(), 'updated_at' => now(),
            ],
            [
                'news_category_id' => 2,
                'title'            => 'SD Biosensor incluida en la recomendación oficial de la OMS, fortaleciendo su presencia global',
                'summary'          => 'La inclusión en el listado de la OMS consolida a SD Biosensor como referente mundial en diagnóstico rápido.',
                'content'          => null,
                'image_path'       => null,
                'is_featured'      => false,
                'is_active'        => true,
                'published_at'     => '2026-06-08',
                'created_at'       => now(), 'updated_at' => now(),
            ],
            [
                'news_category_id' => 3,
                'title'            => '29ª Conferencia Anual de la Sociedad Coreana de Microbiología Clínica',
                'summary'          => 'VISÍTENOS EN la 29ª Conferencia Anual de la Sociedad Coreana de Microbiología Clínica en Seúl, Corea.',
                'content'          => null,
                'image_path'       => null,
                'is_featured'      => true,
                'is_active'        => true,
                'published_at'     => '2026-06-16',
                'created_at'       => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
