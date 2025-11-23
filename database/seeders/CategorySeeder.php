<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Furniture',
            'Lighting',
            'Decor',
            'Storage',
            'Bedroom',
            'Office',
            'Outdoor',
        ];

        foreach ($categories as $name) {
            Category::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
