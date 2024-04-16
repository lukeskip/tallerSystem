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
        // Obtener todos los clientes
        $clients = Client::all();

        // Iterar sobre cada cliente
        $clients->each(function ($client)use($faker) {
            // Crear dos proyectos por cada cliente
            for ($i = 0; $i < 2; $i++) {
                Project::create([
                    'name' => 'Proyecto ' . ($i + 1) . ' de ' . $client->name,
                    'address'=>$faker->streetAddress,
                    'comission' => $faker->randomFloat(2, 0, 100),
                    'client_id' => $client->id,
                ]);
            }
        });
    }
}
