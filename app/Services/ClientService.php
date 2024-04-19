<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Client;

class ClientService
{
    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data)
    {
        $client->update($data);
        return $client;    
    }

    public function delete(Client $project)
    {
        $client->delete();
    }

    public function getById($id)
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
       
        $clients = Client::with('projects');

        
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