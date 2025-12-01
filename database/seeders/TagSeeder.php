<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryTags = [
            'Bedroom'      => ['Bedframe', 'Nightstand', 'Wardrobe', 'Dresser', 'Mattress', 'Bedside Lamp', 'Vanity Table', 'Headboard'],
            'Sitting Room' => ['Sofa', 'Armchair', 'Coffee Table', 'TV Stand', 'Recliner', 'Side Table', 'Bookshelf', 'Throw Pillows'],
            'Accessories'  => ['Wall Clock', 'Ceramic Vase', 'Throw Blanket', 'Candle Holder', 'Artificial Plant', 'Photo Frame', 'Decorative Bowl'],
            'Kitchen'      => ['Bar Stool', 'Dining Chair', 'Dining Table', 'Utensil Rack', 'Kitchen Shelf', 'Cookware Set', 'Serving Tray', 'Kitchen Island'],
            'Lighting'     => ['Pendant Light', 'Floor Lamp', 'Desk Lamp', 'Chandelier', 'Wall Sconce', 'Table Lamp', 'Reading Light'],
            'Decor'        => ['Wall Art', 'Mirror', 'Rug', 'Indoor Plant', 'Decorative Tray', 'Sculpture', 'Clock', 'Centerpiece'],
            'Storage'      => ['Cabinet', 'Drawer Chest', 'Shoe Rack', 'Floating Shelf', 'Storage Basket', 'TV Console', 'Sideboard', 'Bookshelf', 'Closet Box'],
            'Office'       => ['Office Chair', 'Work Desk', 'Monitor Stand', 'Desk Lamp', 'Desk Organizer', 'Filing Cabinet', 'Laptop Stand', 'Drawer Unit'],
            'Outdoor'      => ['Patio Chair', 'Garden Table', 'Umbrella', 'Outdoor Sofa', 'Planter Pot', 'Outdoor Rug', 'Lantern Light', 'Garden Bench', 'Hammock'],
        ];

        foreach ($categoryTags as $categoryName => $tags) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($tags as $tag) {
                Tag::create([
                    'category_id' => $category->id,
                    'name'        => $tag,
                    'slug'        => Str::slug($tag . ' ' . $categoryName),
                ]);
            }
        }
    }
}
