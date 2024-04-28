<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;
use Carbon\Carbon;

class ProjectService
{
    public function store($request)
    {
        return Project::create($request);     
    }

    public function create(){
        return $fields = Utils::getFields('projects');
    }

    public function edit ($id){
        $project =  Project::with(['client'])->find($id);
        $project =  [
            'name'=> ['value'=>$project->name,'type'=>'string'],
            'address'=> ['value'=>$project->address,'type'=>'string'],
            'comission'=> ['value'=>$project->comission,'type'=>'number'],
            'client_id'=> ['value'=>$project->client_id,'type'=>'number'],
        ];
    
        $fields = Utils::getFields('projects');
        
        return ["item"=>$project,"fields"=>$fields];
    }

    public function update($id, $request)
    {
        $project = Project::find($id);
        $project->update($request);
        return $project;      
    }

    public function delete($id)
    {
        $project = Project::find($id);
        $project->delete();
    }

    public function getById($id,$edit=false)
    {
        $project =  Project::with(['client','invoices','files'])->find($id);

        if ($project) {

            $incomes = $project->incomes;
            $outcomes = $project->outcomes;
            
            if($incomes){
                $incomesTotal = $incomes->sum('amount');
            }else{
                $incomesTotal = 0;
            }

            if($outcomes){
                $outcomesTotal = $outcomes->sum('amount');
            }else{
                $outcomesTotal = 0;
            }

            $balance = $incomesTotal - $outcomesTotal;

            return [
                'id' => $project->id,
                'name' => $project->name,
                'address' => $project->address,
                'comission' => $project->comission,
                'incomesTotal'=>$incomesTotal,
                'outcomesTotal'=>$outcomesTotal,
                'balance'=>$balance,
                'client' => [
                    'id' => $project->client->id,
                    'name' => $project->client->name,
                ],
                'invoices' => $project->invoices->map(function ($invoice) {
                    return [
                        "id"=>$invoice->id,
                        "file"=>$invoice->id,
                        "amount" => $invoice->amount,
                        'status'=> $invoice->status,
                        'format_date' => $invoice->format_date
                    ];
                }),
                'files' => $project->files->map(function ($file) {
                    return [
                        "id"=>$file->id,
                        "name"=>$file->name,
                        "url"=>$file->url,
                        "extension" => $file->extension,
                        'format_date' => $file->format_date
                    ];
                }),
                'format_date' => $project->format_date,
            ];
            
        } else {
            return null;
        }
       
    }

    public function getAll($request = null)
    {
       
        $projects = Project::with('client')->orderBy('id','desc');

        
        if ($request &&  $request->input('search')) {
            $projects->where('name', 'like', '%' . $request->input('search') . '%')
            ->orWhereHas('client', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            });
        }
        

        if ($request &&  $request->input('orderBy')) {
            $projects->orderBy($request->input('orderBy'),'desc');
        }

        $projects = $projects->paginate();
        
        $projects->getCollection()->transform(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'client' => $project->client->name,
                'format_date'=> $project->format_date,
            ];
        });

        return $projects;
        


    }


}