<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['name' => 'Vendor '.$sequence->index],
            ))
            ->hasProducts(5)
            ->create();
    }
}
