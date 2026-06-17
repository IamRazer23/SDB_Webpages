<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'category', 'title', 'file_path', 'published_at', 'sort_order',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
