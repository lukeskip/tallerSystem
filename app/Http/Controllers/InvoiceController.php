<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Inertia\Inertia;
use App\Utils\Utils;
use App\Services\ValidateDataService;
use App\Services\UserService;

class InvoiceController extends Controller
{
    private $service;
    private $userService;
    private $rules;

    public function __construct(InvoiceService $invoiceService, UserService $userService)
    {
        $this->middleware('can:read invoice', ['only' => ['index', 'show']]);
        $this->middleware('can:create invoice', ['only' => ['create', 'store']]);
        $this->middleware('can:edit invoice', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete invoice', ['only' => ['destroy']]);

        $this->service = $invoiceService;
        $this->userService = $userService;
        $this->rules = [
            'status' => 'required|string',
            'iva' => 'nullable',
            'fee' => 'nullable',
            'project_id' => 'required|numeric|gt:0',
            'agent_comission' => 'numeric|gt:0',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = $this->service->getAll($request);
        return Inertia::render('Invoice/Invoices', [
            'invoices' => $invoices,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = Utils::getFields('invoices');
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            $item = $this->service->store($validatedData['data']);
            return Inertia::location(route('cotizaciones.show', $item->id));
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $providers = $this->service->getProviders();
        $categories = $this->service->getCategoriesByInvoice($id);
        $invoice = $this->service->getById($id);
        return Inertia::render('Invoice/InvoiceDetail', [
            'invoice' => $invoice,
            'categories' => $categories,
            'providers' => $providers
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

        if ($validatedData['status']) {
            $this->service->update($id, $validatedData['data']);
            return response()->json(['message' => "actualizado con éxito"]);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $projectID = $this->service->delete($id);
        return Inertia::location(route('proyectos.show', $projectID));
    }

    public function comissionsByUser($invoiceId, $userId)
    {
        $comissions = $this->service->comissionsByUser($invoiceId, $userId);
        $user = $this->userService->getById($userId);

        return Inertia::render('Comissions/ComissionDetail', [
            'comissions' => $comissions,
            'user' => $user,
            'total' => Utils::publishMoney($comissions->sum('agent_comission_raw'))

        ]);
    }
}
