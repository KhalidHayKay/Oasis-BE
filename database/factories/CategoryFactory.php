<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'             => $this->faker->word(),
            'description'      => $this->faker->sentence(10),
            'slug'             => $this->faker->unique()->slug(),
            'popularity_score' => $this->faker->numberBetween(1, 100),
        ];
    }
}
