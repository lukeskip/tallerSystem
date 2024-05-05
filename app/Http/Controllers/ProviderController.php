<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Services\ProviderService;
use App\Services\ValidateDataService;
use Inertia\Inertia;
use App\Utils\Utils;


class ProviderController extends Controller
{
    public function __construct(ProviderService $providerService)
    {
        $this->middleware('can:read provider', ['only' => ['index', 'show']]);
        $this->middleware('can:create provider', ['only' => ['create', 'store']]);
        $this->middleware('can:edit provider', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete provider', ['only' => ['destroy']]);

        $this->service = $providerService;
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
        $providers = $this->service->getAll($request);
        return Inertia::render('Provider/Providers', [
            'providers' => $providers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = Utils::getFields('providers');
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
            return  $this->service->create($validatedData['data']);   
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
       
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $provider = $this->service->getById($id);
        return Inertia::render('Provider/ProviderDetail', [
            'provider' => $provider,
            
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
    public function update(Request $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->delete($id);
    }
}
