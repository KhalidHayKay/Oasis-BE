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
        return [
            'name'             => $this->faker->words(3, true),
            'description'      => $this->faker->paragraph(2),
            'price'            => json_encode([
                'amount'   => $this->faker->randomFloat(0, 10, 500),
                'currency' => 'USD',
            ]),
            'stock'            => $this->faker->numberBetween(5, 20),
            'popularity_score' => $this->faker->numberBetween(1, 100),
        ];
    }
}
