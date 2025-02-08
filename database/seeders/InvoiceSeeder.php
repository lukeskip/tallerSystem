<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Project;
use App\Utils\Utils;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            $numberOfInvoices = rand(1, 5); 

            for ($i = 0; $i < $numberOfInvoices; $i++) {
                $id = Utils::generateInvoiceId();
                Invoice::create([
                    'id'=> $id,
                    'status'=>'pending',
                    'project_id' => $project->id,
                ]);
            }
        }
    }
}
