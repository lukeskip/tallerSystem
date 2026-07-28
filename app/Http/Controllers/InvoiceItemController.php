<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Services\InvoiceItemService;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use App\Services\ValidateDataService;

class InvoiceItemController extends Controller
{
    protected $invoiceItemService;
    protected $rules;
    protected $service;

    public function __construct(InvoiceItemService $invoiceItemService)
    {
        $this->middleware('can:read invoice_item', ['only' => ['index', 'show']]);
        $this->middleware('can:create invoice_item', ['only' => ['create', 'store']]);
        $this->middleware('can:edit invoice_item', ['only' => ['edit', 'update', 'invoiceItemsOrder']]);
        $this->middleware('can:delete invoice_item', ['only' => ['destroy']]);

        $this->service = $invoiceItemService;
        $this->rules = $this->service->rules();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoiceItems = $this->service->getAll();

        return Inertia::render('InvoiceItems/Index', [
            'invoiceItems' => $invoiceItems,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (isset($_GET['parentId'])) {
            $id = $_GET['parentId'];
        } else {
            $id = false;
        }

        $fields = Utils::getFields('invoice_items', $id);
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
            
            if ($request->hasFile('file')) {
                $request->merge(['invoice_item_id' => $item->id]);
                $fileService = app(\App\Services\FileService::class);
                $fileService->create($request);
            } elseif ($request->filled('mobile_file_id')) {
                $item->files()->sync([$request->input('mobile_file_id')]);
            }

            return $item;
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoiceItem = $this->service->getById($id);
        return Inertia::render('InvoiceItem/InvoiceItemDetail', [
            'invoiceItem' => $invoiceItem,
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
            $item = $this->service->update($id, $validatedData['data']);
            if ($request->has('remove_file') && $request->remove_file == 'true') {
                $fileService = app(\App\Services\FileService::class);
                if ($item->files) {
                    foreach ($item->files as $oldFile) {
                        $item->files()->detach($oldFile->id);
                        $fileService->delete($oldFile->id);
                    }
                }
            }

            if ($request->hasFile('file')) {
                $fileService = app(\App\Services\FileService::class);
                
                if ($item->files) {
                    foreach ($item->files as $oldFile) {
                        $item->files()->detach($oldFile->id);
                        $fileService->delete($oldFile->id);
                    }
                }

                $request->merge(['invoice_item_id' => $item->id]);
                $fileService->create($request);
            } elseif ($request->filled('mobile_file_id')) {
                $fileService = app(\App\Services\FileService::class);
                if ($item->files) {
                    foreach ($item->files as $oldFile) {
                        $item->files()->detach($oldFile->id);
                        $fileService->delete($oldFile->id);
                    }
                }
                $item->files()->sync([$request->input('mobile_file_id')]);
            }

            return $item;
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function importCSV(Request $request, $id)
    {
        return $this->service->importCSV($request, $id);
    }

    public function invoiceItemsOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|numeric',
            'items.*.order' => 'required|numeric',
            'items.*.category_id' => 'nullable|numeric',
        ]);

        $itemsRequest = collect($request->input('items'));
        $itemsIds = $itemsRequest->pluck('id');

        $items = InvoiceItem::whereIn('id', $itemsIds)->get();

        $items->each(function ($item) use ($itemsRequest) {
            $itemData = $itemsRequest->firstWhere('id', $item->id);
            if ($itemData) {
                $item->update([
                    'order' => $itemData['order'],
                    'category_id' => $itemData['category_id']
                ]);
            }
        });

        return response()->json([
            'message' => 'Orden de items actualizado correctamente',
        ]);
    }
}
