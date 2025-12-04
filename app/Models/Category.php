<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'image',
        'popularity_score',
    ];

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
