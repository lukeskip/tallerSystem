<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Inertia\Inertia;   
use App\Utils\Utils;
use Illuminate\Http\Request;

class ClientService
{
    public function store($request)
    {
        return Client::create($request);     
    }

    public function create(){
        return $fields = Utils::getFields('clients');
    }

    public function edit ($id){
        $client = $this->getById($id);

        $client = [
                'name'=>['value'=>$client['name'],'type'=>'string'],    
                'contact_name'=>['value'=>$client['contact_name'],'type'=>'string'],    
                'phone'=>['value'=>$client['phone'],'type'=>'string'],    
                'address'=>['value'=>$client['address'],'type'=>'string'],    
                'email'=>['value'=>$client['email'],'type'=>'string'],      
        ];
    
        $fields = Utils::getFields('clients');
        
        return ["item"=>$client,"fields"=>$fields];
    }

    public function update($id, $request)
    {    
        $client = Client::find($id);
        $client->update($request);
        return $client;    
    }

    public function delete($id)
    {
        $client = Client::find($id);
        return $client->delete();
    }

    public function getById($id,$edit = false)
    {
        $client =  Client::with(['projects'])->find($id);

        if ($client) {

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

    
}