<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomImages = [
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308',
            'https://images.unsplash.com/photo-1526178613658-3f1622045544',
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
            'https://images.unsplash.com/photo-1465101046530-73398c7f28ca',
            'https://images.unsplash.com/photo-1519985176271-adb1088fa94c',
            'https://images.unsplash.com/photo-1502086223501-7ea6ecd79368',
            'https://images.unsplash.com/photo-1465101178521-c1a4c8a0a8b7',
            'https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6',
            'https://images.unsplash.com/photo-1470770841072-f978cf4d019e',
            'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429',
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f',
            'https://images.unsplash.com/photo-1513708922415-9d8b3b1a6b9b',
            'https://images.unsplash.com/photo-1511918984145-48de785d4c4e',
            'https://images.unsplash.com/photo-1503602642458-232111445657',
            'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2',
            'https://images.unsplash.com/photo-1518717758536-85ae29035b6d',
            'https://images.unsplash.com/photo-1516979187457-637abb4f9353',
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308',
            'https://images.unsplash.com/photo-1526178613658-3f1622045544',
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308',
            'https://images.unsplash.com/photo-1526178613658-3f1622045544',
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
            'https://images.unsplash.com/photo-1465101046530-73398c7f28ca',
            'https://images.unsplash.com/photo-1519985176271-adb1088fa94c',
            'https://images.unsplash.com/photo-1502086223501-7ea6ecd79368',
            'https://images.unsplash.com/photo-1465101178521-c1a4c8a0a8b7',
            'https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6',
            'https://images.unsplash.com/photo-1470770841072-f978cf4d019e',
            'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429',
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f',
            'https://images.unsplash.com/photo-1513708922415-9d8b3b1a6b9b',
            'https://images.unsplash.com/photo-1511918984145-48de785d4c4e',
            'https://images.unsplash.com/photo-1503602642458-232111445657',
            'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2',
            'https://images.unsplash.com/photo-1518717758536-85ae29035b6d',
            'https://images.unsplash.com/photo-1516979187457-637abb4f9353',
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308',
            'https://images.unsplash.com/photo-1526178613658-3f1622045544',
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
            'https://images.unsplash.com/photo-1465101059347-7630a1cbd1ac',
            'https://images.unsplash.com/photo-1465101060972-2c1e3b6c8a7b',
            'https://images.unsplash.com/photo-1465101079483-2c1e3b6c8a7c',
            'https://images.unsplash.com/photo-1465101089483-2c1e3b6c8a7d',
            'https://images.unsplash.com/photo-1465101099483-2c1e3b6c8a7e',
            'https://images.unsplash.com/photo-1465101109483-2c1e3b6c8a7f',
            'https://images.unsplash.com/photo-1465101119483-2c1e3b6c8a7g',
            'https://images.unsplash.com/photo-1465101129483-2c1e3b6c8a7h',
            'https://images.unsplash.com/photo-1465101139483-2c1e3b6c8a7i',
            'https://images.unsplash.com/photo-1465101149483-2c1e3b6c8a7j',
            'https://images.unsplash.com/photo-1465101159483-2c1e3b6c8a7k',
            'https://images.unsplash.com/photo-1465101169483-2c1e3b6c8a7l',
            'https://images.unsplash.com/photo-1465101179483-2c1e3b6c8a7m',
            'https://images.unsplash.com/photo-1465101189483-2c1e3b6c8a7n',
            'https://images.unsplash.com/photo-1465101199483-2c1e3b6c8a7o',
            'https://images.unsplash.com/photo-1465101209483-2c1e3b6c8a7p',
            'https://images.unsplash.com/photo-1465101219483-2c1e3b6c8a7q',
            'https://images.unsplash.com/photo-1465101229483-2c1e3b6c8a7r',
            'https://images.unsplash.com/photo-1465101239483-2c1e3b6c8a7s',
            'https://images.unsplash.com/photo-1465101249483-2c1e3b6c8a7t',
        ];
        return [
            'url' => fake()->randomElement($randomImages),
            'order' => fake()->numberBetween(0, 4)
        ];
    }
}
