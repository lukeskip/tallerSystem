<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia; 

class ProjectService
{
    public function create($request)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $project = Project::create($validatedData['data']); 
            return response()->json(['redirect'=> 'proyectos/'.$project->id]);  
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
        
    }

    public function update($id, $request)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $project = Project::find($id);
            $project->update($validatedData['data']);
            return $project;    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }  
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

            if($edit){
                return [
                    'name'=> ['value'=>$project->name,'type'=>'string'],
                    'address'=> ['value'=>$project->address,'type'=>'string'],
                    'comission'=> ['value'=>$project->comission,'type'=>'number'],
                    'client_id'=> ['value'=>$project->client_id,'type'=>'number'],
                ];
            }else{
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
            }
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

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
            'comission' => 'required|numeric|gt:0',
            'client_id'=> 'numeric|gt:0'
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }

            return ['status'=>false,'errors'=>$fieldErrors];
        }

        $cleanedData = $validator->validated();

        return ['status'=>true,'data'=>$cleanedData];
    }
}