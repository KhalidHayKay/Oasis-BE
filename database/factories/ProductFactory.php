<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hexColors = [
            '#D8D8D8', // Soft Beige
            '#A8D5BA', // Sage Green
            '#8FA3C7', // Muted Blue
            '#A49B8A', // Warm Taupe
            '#2C3E50', // Deep Charcoal
            '#D6A6A1', // Dusty Rose
            '#C0C0C0', // Taupe Gray
        ];

        return [
            'name'             => $this->faker->words(3, true),
            'description'      => $this->faker->paragraph(2),
            'price'            => [
                'amount'   => $this->faker->randomFloat(0, 10, 300),
                'currency' => 'USD',
                'discount' => $this->faker->numberBetween(0, 50),
            ],
            'stock'            => $this->faker->numberBetween(5, 20),
            'popularity_score' => $this->faker->numberBetween(1, 100),
            'rating'           => $this->faker->numberBetween(1, 5),
            'colors'           => $this->faker->randomElements($hexColors, $this->faker->numberBetween(1, 3)),

            'category_id'      => $this->faker->randomElement(Category::pluck('id')->toArray()),
        ];
    }
}
