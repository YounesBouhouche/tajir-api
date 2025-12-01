<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Younes',
            'email' => 'younes.bouhouche12@gmail.com',
            'password' => 'iu_1EBgzRh5jhlV',
        ]);
        $this->call([
            UserAddressesSeeder::class,
            ProductTypesSeeder::class,
            ProductsSeeder::class,
        ]);
    }
}
