<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street_address' => fake()->streetAddress,
            'city' => fake()->city,
            'state' => fake()->city,
            'country' => fake()->country,
            'zip_code' => fake()->numberBetween(10000, 99999),
        ];
    }
}
