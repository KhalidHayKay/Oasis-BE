<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspiration extends Model
{
    /** @use HasFactory<\Database\Factories\InspirationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'image_url',
        'category',
        'display_order',
        'height',
        'is_active',
    ];

    protected function cast(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
