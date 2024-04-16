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
                'address' => $project->address,
                'client' => [
                    'id' => $project->client->id,
                    'name' => $project->client->name,
                ],
                'invoices' => $project->invoices->map(function ($invoice) {
                    return [
                        "id"=>$invoice->id,
                        "amount" => $invoice->amount,
                        'status'=> $invoice->status,
                        'format_date' => $invoice->format_date
                    ];
                }),
                'format_date' => $project->format_date,
            ];
        } else {
            return null;
        }
       
    }

    public function getAll()
    {
        $projects = Project::with('client')->paginate();

        
        $projects->getCollection()->transform(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'client' => [
                    'id' => $project->client->id,
                    'name' => $project->client->name,
                ],
                'format_date'=> $project->format_date,
            ];
        });

        return $projects;
        


    }
}