<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();


        Client::create([
            'name' => 'Luis Álvarez',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);

        Client::create([
            'name' => 'Bella Milmo',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);
        
        Client::create([
            'name' => 'Rebeca Rangel',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);

        Client::create([
            'name' => 'Ángeles Rión',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);

        Client::create([
            'name' => 'Mariana Gómez',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);

        Client::create([
            'name' => 'Andrea Heguevich',
            'contact_name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->safeEmail,
        ]);
    }
}
