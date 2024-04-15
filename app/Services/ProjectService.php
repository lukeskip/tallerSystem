<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function create(array $data)
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data)
    {
        $project->update($data);
        return $project;    
    }

    public function delete(Project $project)
    {
        $project->delete();
    }

    public function getById($id)
    {
        $project =  Project::with(['client','invoices'])->find($id);

        if ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'format_date' => $project->format_date,
                'client' => [
                    'id' => $project->client->id,
                    'name' => $project->client->name,
                ],
                'invoices' => $project->invoices->map(function ($invoice) {
                    return [
                        "total_amount" => $invoice->total_amount,
                        'status'=> $invoice->status,
                        'format_date' => $invoice->format_date
                    ];
                }),
            ];
        } else {
            return null; // O manejar el caso en que no se encuentra el proyecto con el ID dado
        }
       
    }

    public function getAll()
    {
        $projects = Project::with('client')->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'format_date'=> $project->format_date,
                'client' => [
                    'id' => $project->client->id,
                    'name' => $project->client->name,
                ],
            ];
        });

        return $projects;
    }
}