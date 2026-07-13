<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\ValidateDataService;
use Inertia\Inertia;
use App\Utils\Utils;

class OrderController extends Controller
{
    protected $service;
    protected $rules;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('can:read order', ['only' => ['index', 'show']]);
        $this->middleware('can:create order', ['only' => ['create', 'store']]);
        $this->middleware('can:edit order', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete order', ['only' => ['destroy']]);

        $this->service = $orderService;
        $this->rules = [
            'invoice_id' => 'required|string|exists:invoices,id',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'description' => 'nullable|string',
            'unit_cost' => 'required|numeric',
            'units' => 'required|integer|min:1',
            'has_iva' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }

    public function index(Request $request)
    {
        $orders = $this->service->getAll($request);
        return Inertia::render('Order/Orders', [
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $fields = Utils::getFields('orders');
        return response()->json($fields);
    }

    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            return $this->service->create($validatedData['data']);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function show($id)
    {
        $order = $this->service->getById($id);
        return Inertia::render('Order/OrderDetail', [
            'order' => $order,
        ]);
    }

    public function edit($id)
    {
        $fields = $this->service->edit($id);
        return response()->json($fields);
    }

    public function update(Request $request, Order $order)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            return $this->service->update($order, $validatedData['data']);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['status' => 'success']);
    }
}
