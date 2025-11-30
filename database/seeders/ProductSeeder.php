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
        $imageGroups = ProductImageSeeder::getImageGroups();

        shuffle($imageGroups);

        $productsToCreate = 150;

        Product::factory($productsToCreate)->create()->each(function ($product, $index) use ($imageGroups) {
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
    }
}
