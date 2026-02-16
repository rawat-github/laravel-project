<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('address_book')->insert([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'country' => fake()->country(),
                'city' => fake()->city(),
                'street' => fake()->streetAddress(),
                'email' => fake()->unique()->email(),
                'phone' => fake()->phoneNumber()
            ]);
        }
    }
}
