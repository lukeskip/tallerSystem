<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Obtener todos los clientes
        $clients = Client::all();

        // Iterar sobre cada cliente
        $clients->each(function ($client) {
            // Crear dos proyectos por cada cliente
            for ($i = 0; $i < 2; $i++) {
                Project::create([
                    'name' => 'Proyecto ' . ($i + 1) . ' de ' . $client->name,
                    'client_id' => $client->id,
                ]);
            }
        });
    }
}
