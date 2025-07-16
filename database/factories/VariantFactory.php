<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $promoted_price = fake()->randomElement([null, fake()->numberBetween(0, 1000)]);
        return [
            'name' => fake()->word(),
            'quantity' => fake()->numberBetween(0, 1000),
            'price' => fake()->numberBetween(0, 1000),
            'promoted_price' => $promoted_price,
            'promoted_price_end' =>
                isset($promoted_price) ? now()->addDays(fake()->numberBetween(0, 1000)) : null
        ];
    }
}
