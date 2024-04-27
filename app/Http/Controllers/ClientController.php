<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Services\ValidateDataService;
use Inertia\Inertia;
use App\Utils\Utils;

class ClientController extends Controller
{
    protected $service;

    public function __construct(ClientService $clientService)
    {
        $this->service = $clientService;
        $this->rules = [
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'email' => 'required|email',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = $this->service->getAll($request);
        return Inertia::render('Client/Clients', [
            'clients' => $clients,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = $this->service->create();
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            return $item = $this->service->store($validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = $this->service->getById($id);
        return Inertia::render('Client/ClientDetail', [
            'client' => $client,
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fields = $this->service->edit($id);
        return response()->json($fields);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            $item = $this->service->update($id,$validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return Inertia::location(route('clientes.index'));
        
    }

    
}
