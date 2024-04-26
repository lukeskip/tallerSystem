<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Services\ProviderService;
use Inertia\Inertia;
use App\Utils\Utils;


class ProviderController extends Controller
{
    public function __construct(ProviderService $providerService)
    {
        $this->service = $providerService;
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
       return  $this->service->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        //
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
