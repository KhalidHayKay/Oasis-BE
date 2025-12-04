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
            [
                'title'       => 'Bedroom',
                'description' => 'Experience the serenity and comfort of thoughtfully designed bedroom furniture where restful relaxation meets elegant style for a peaceful night’s sleep.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/spacejoy-eyEy5YZhSvU-unsplash_qboeoj?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Sitting Room',
                'description' => 'Step into a world of comfort and sophistication with our sitting room furniture, where cozy meets contemporary for the ultimate lounge experience in style.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/iridial-v8OcSTA-Ny4-unsplash_jsixyo?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Accessories',
                'description' => 'Elevate your living space with chic accessories that blend beauty and practicality, turning everyday items into luxurious additions to your home.',

                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/sirius-harrison-zhCOL6LbZv0-unsplash_uuwfj3?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Kitchen',
                'description' => 'Transform your kitchen into a space where functionality meets modern elegance, offering designs that bring both style and convenience to your culinary adventures.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/collov-home-design--aDGbdTsBZg-unsplash_hjnbsw?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Lighting',
                'description' => 'Illuminate your space with innovative lighting designs that fuse form and function, creating an atmosphere that’s both bright and beautifully serene.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/mohid-tahir-Cbo_O5_0CYc-unsplash_glafk7?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Decor',
                'description' => 'Bring warmth and personality to your home with décor pieces that reflect your unique style, turning every room into a captivating blend of luxury and comfort.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/gwg-outlet-cj-APyc-iM8-unsplash_kc0lol?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Storage',
                'description' => 'Maximize your space with stylish storage solutions that combine functionality with high-end design, keeping your home organized and clutter-free while maintaining an elegant touch.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/pickawood-5wCGsFe62Ls-unsplash_l3ysxi?_a=BAMAAAfm0',
            ],
            [
                'title'       => 'Office',
                'description' => 'Transform your workspace into a modern hub of productivity with office furniture that promotes both comfort and innovation for a seamless work-life balance.',
                'image'       => 'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/ergonofis-tdnYk4qOGhc-unsplash_kt4j3f?_a=BAMAAAfm0',
            ],
        ];

        foreach ($categories as $category) {
            Category::factory()->create([
                'name'        => $category['title'],
                'slug'        => Str::slug($category['title']),
                'description' => $category['description'],
                'image'       => $category['image'],
            ]);
        }
    }
}
