<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;
use Faker\Factory as Faker;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

     
        $client = Client::where('name', 'Luis Álvarez')->first();;
        Project::create([
            'name' => 'San Jerónimo',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);
        Project::create([
            'name' => 'Valle de Bravo',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);
        Project::create([
            'name' => 'Acapulco',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);
        Project::create([
            'name' => 'Vail',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);

        
        $client = Client::where('name', 'Bella Milmo')->first();;
        Project::create([
            'name' => 'Monterrey',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);

        $client = Client::where('name', 'Rebeca Rangel')->first();;
        Project::create([
            'name' => 'Tamán Condesa',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);

        $client = Client::where('name', 'Mariana Gómez')->first();;
        Project::create([
            'name' => 'Dewi',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);
        Project::create([
            'name' => 'Quiroga',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);

        $client = Client::where('name', 'Andrea Heguevich')->first();;
        Project::create([
            'name' => 'Monterrey',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);
        Project::create([
            'name' => 'Lomas',
            'address'=>$faker->streetAddress,
            'comission' => $faker->randomFloat(2, 0, 100),
            'client_id' => $client->id,
        ]);

       
        
        
    }
}
