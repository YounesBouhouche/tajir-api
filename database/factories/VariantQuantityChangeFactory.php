<?php

namespace Database\Factories;

use App\Models\VariantQuantityChange;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantQuantityChangeFactory extends Factory
{
    protected $model = VariantQuantityChange::class;

    public function definition(): array
    {
        return [
            'amount' => fake()->numberBetween(-100, 100),
            'note' => fake()->text()
        ];
    }
}
