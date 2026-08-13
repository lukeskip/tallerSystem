<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    protected InvoiceService $service;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->service = $invoiceService;
    }

    /**
     * Display a listing of invoices / cotizaciones.
     */
    public function index(Request $request): JsonResponse
    {
        $invoices = $this->service->getAll($request);
        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }

    /**
     * Display the specified invoice / cotización.
     */
    public function show(int|string $id): JsonResponse
    {
        $invoice = $this->service->getById($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice,
        ]);
    }
}
