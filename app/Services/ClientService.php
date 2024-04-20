<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;    

class ClientService
{
    public function create($request)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            return Client::create($validatedData['data']);   
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
    }

    public function update($id, $request)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $client = Client::find($id);
            $client->update($validatedData['data']);
            return $client;    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }     
    }

    public function delete($id)
    {
        $client = Client::find($id);
        $client->delete();
        return Inertia::location(route('clientes.index'));
        
    }

    public function getById($id,$edit = false)
    {
        $client =  Client::with(['projects'])->find($id);

        if ($client) {

            if($edit){
                return [
                    'name'=>['value'=>$client->name,'type'=>'string'],    
                    'contact_name'=>['value'=>$client->contact_name,'type'=>'string'],    
                    'phone'=>['value'=>$client->phone,'type'=>'string'],    
                    'address'=>['value'=>$client->address,'type'=>'string'],    
                    'email'=>['value'=>$client->email,'type'=>'string'],    
                    'project_id'=>['value'=>$client->project_id,'type'=>'number'],    
                ];
            }else{

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'contact_name' => $client->contact_name,
                    'phone' => $client->phone,
                    'address' => $client->address,
                    'email' => $client->email,
                    'projects' => $client->projects->map(function ($project) {
                        return [
                            "id"=>$project->id,
                            "name" => $project->name,
                            'format_date' => $project->format_date
                        ];
                    }),
                ];
            }
        } else {
            return null;
        }
       
    }

    public function getAll($request = null)
    {
       
        $clients = Client::with('projects')->orderBy('id','desc');

        
        if ($request &&   $request->input('search')) {
            $clients->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $clients = $clients->paginate();
        
        $clients->getCollection()->transform(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'format_date'=> $client->format_date,
            ];
        });

        return $clients;

    }

    public function getClients (){
        $clients = Client::all();
        
        $clients->transform(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
            ];
        });

        return $clients;
    }

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact_name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
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