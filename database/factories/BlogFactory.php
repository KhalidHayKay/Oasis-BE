<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    private array $titlePool = [
        'The Art of Minimalism: How to Achieve a Sleek Look',
        'Choosing the Perfect Sofa: A Guide to Style and Comfort',
        'Sustainable Furniture: Eco-Friendly Choices for a Modern Home',
        'Maximizing Small Spaces: Innovative Furniture Solutions',
        'Mixing and Matching: Creating Harmony with Different Styles',
        'Accent Pieces That Pop: Adding Character to Your Home',
        'Open Shelving: The Secret to a Stylish Living Room',
        'Mid-Century Modern: Timeless Pieces for Contemporary Spaces',
        'The Power of Texture: Layering Materials in Interior Design',
        'Transforming Your Living Space: Top Trends in Modern Furniture',
        'How to Choose the Right Rug for Every Room',
        'Scandinavian Design: Simple, Functional, and Beautiful',
        'Statement Lighting: Elevate Your Space with the Perfect Fixture',
        'From Clutter to Calm: Smart Storage Solutions for Every Room',
        'Neutral Palettes Done Right: Designing a Warm and Inviting Home',

    ];

    /** All available hashtags — stored with # prefix per convention. */
    private array $hashtagPool = [
        '#interior-design',
        '#minimalism',
        '#sustainable-living',
        '#small-spaces',
        '#modern-furniture',
        '#scandinavian-design',
        '#mid-century-modern',
        '#accent-pieces',
        '#eco-friendly',
        '#home-decor',
    ];

    public function definition(): array
    {
        // Pick 1–3 unique hashtags for this blog
        $hashtags = $this->faker->randomElements(
            $this->hashtagPool,
            rand(1, 3)
        );

        return [
            'title'       => $this->faker->unique()->randomElement($this->titlePool),
            'description' => $this->faker->sentence(rand(12, 20)),
            'cover_image' => 'https://picsum.photos/seed/' . $this->faker->word() . '/800/500',
            'body'        => $this->generateBody(),
            'hashtags'    => $hashtags,
        ];
    }

    private function generateBody(): string
    {
        return implode("\n\n", $this->faker->paragraphs(rand(5, 9)));
    }
}
