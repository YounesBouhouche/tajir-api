<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(15)
            ->has(
                ProductImage::factory(5)
                    ->sequence(fn (Sequence $sequence) => ['order' => $sequence->index % 5])
            )
            ->has(Variant::factory(3))
            ->for(User::findOrFail(1))
            ->create();
    }
}
