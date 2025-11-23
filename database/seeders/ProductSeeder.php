<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('Please run CategorySeeder first!');
            return;
        }

        $imageGroups = ProductImageSeeder::getImageGroups();

        shuffle($imageGroups);

        $productsToCreate = 50;

        Product::factory($productsToCreate)->create()->each(function ($product, $index) use ($categories, $imageGroups) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')
            );

            $imageGroup = $imageGroups[$index % count($imageGroups)];

            $firstImageId = null;

            foreach ($imageGroup as $i => $url) {
                $image = $product->images()->create([
                    'image_path' => $url,
                    'alt_text'   => $product->name . ' - View ' . ($i + 1),
                ]);

                if ($i === 0) {
                    $firstImageId = $image->id;
                }
            }

            $product->update(['featured_image_id' => $firstImageId]);
        });

        $this->command->info("Created {$productsToCreate} products with image groups.");
    }
}
