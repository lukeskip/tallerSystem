<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Invoice;
use App\Models\Project;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Obtener todos los clientes
        $projects = Project::all();

        // Generar cotizaciones de ejemplo relacionadas a los clientes
        foreach ($projects as $project) {
            $numberOfInvoices = rand(1, 5); // Generar entre 1 y 5 cotizaciones por cliente

            for ($i = 0; $i < $numberOfInvoices; $i++) {
                Invoice::create([
                    'project_id' => $project->id,
                ]);
            }
        }
    }
}
