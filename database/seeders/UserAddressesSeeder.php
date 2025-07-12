<?php

namespace Database\Seeders;

use App\Http\Resources\AddressCollection;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::factory(3)
            ->for(User::findOrFail(11))
            ->create();
    }
}
