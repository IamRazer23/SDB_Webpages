<?php

namespace Database\Seeders;

use App\Models\Download;
use Illuminate\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run(): void
    {
        Download::insert([
            [
                'category' => 'software',
                'title' => 'STANDARD LMS (LipidoCare Management System)_v3.1.0',
                'file_path' => null,
                'published_at' => '2026-04-14',
                'sort_order' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'category' => 'software',
                'title' => 'STANDARD DMS (Diabetes Management System)_v1.7.0',
                'file_path' => null,
                'published_at' => '2023-09-26',
                'sort_order' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'category' => 'etc',
                'title' => 'DOC of Pic Gluco Test',
                'file_path' => null,
                'published_at' => '2023-06-14',
                'sort_order' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'category' => 'software',
                'title' => 'STANDARD MMS (MultiCare Management System)_v1.2.3',
                'file_path' => null,
                'published_at' => '2021-08-11',
                'sort_order' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
