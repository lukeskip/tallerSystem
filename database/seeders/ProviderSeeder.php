<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Generar clientes de ejemplo
        for ($i = 0; $i < 20; $i++) {
            Provider::create([
                'name' => $faker->company,
                'contact_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
            ]);
        }
    }
}
