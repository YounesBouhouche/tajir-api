<?php

namespace Database\Seeders;

use App\Http\Resources\ShippingAddressCollection;
use App\Models\ShippingAddress;
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
        ShippingAddress::factory(3)
            ->for(User::findOrFail(1))
            ->create();
    }
}
