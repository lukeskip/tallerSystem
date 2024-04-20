<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\ClientService;
use Inertia\Inertia;
use App\Utils\Utils;

class ClientController extends Controller
{
    public function __construct(ClientService $clientService)
    {
        $this->service = $clientService;
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
        $fields = Utils::getFields('clients');
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return  $this->service->create($request);
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
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
        
    }

    
}
