<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = [
        'title', 'cover_image_path', 'file_path', 'year', 'sort_order',
    ];
}
