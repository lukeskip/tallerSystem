<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use Illuminate\Http\Request;
use App\Services\FabricService;
use App\Services\ValidateDataService;
use Inertia\Inertia;
use App\Utils\Utils;

class FabricController extends Controller
{
    protected $service;
    protected $rules;

    public function __construct(FabricService $fabricService)
    {
        $this->middleware('can:read fabric', ['only' => ['index', 'show']]);
        $this->middleware('can:create fabric', ['only' => ['create', 'store']]);
        $this->middleware('can:edit fabric', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete fabric', ['only' => ['destroy']]);

        $this->service = $fabricService;
        $this->rules = [
            'invoice_id' => 'required|string|exists:invoices,id',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'brand' => 'nullable|string',
            'pattern' => 'nullable|string',
            'color' => 'nullable|string',
            'units' => 'required|numeric|min:1',
        ];
    }

    public function index(Request $request)
    {
        $fabrics = $this->service->getAll($request);
        return response()->json($fabrics);
    }

    public function create(Request $request)
    {
        $fields = Utils::getFields('fabrics');
        return response()->json($fields);
    }

    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            $fabric = $this->service->create($validatedData['data']);
            return $fabric;
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function show($id)
    {
        $fabric = $this->service->getById($id);
        return response()->json($fabric);
    }

    public function edit($id)
    {
        $fields = $this->service->edit($id);
        return response()->json($fields);
    }

    public function update(Request $request, $id)
    {
        $fabric = Fabric::findOrFail($id);
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            $fabric = $this->service->update($fabric, $validatedData['data']);
            return $fabric;
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Fabric deleted successfully']);
    }
}
