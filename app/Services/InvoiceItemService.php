<?php

namespace App\Services;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;
use League\Csv\Reader;
use App\Services\ValidateDataService;
use Illuminate\Validation\Rule;

class InvoiceItemService
{

    public function rules()
    {
        return [
            'label' => [
                'required',
                'string',
            ],
            'description' => 'nullable|string',
            'units' => 'required|numeric',
            'unit_price' => 'required|numeric|gt:0',
            'unit_cost' => 'numeric|gte:0|nullable',
            'provider_id' => 'nullable',
            'user_id' => 'nullable',
            'invoice_id' => 'string|nullable',
            'category'  => 'nullable'
        ];
    }

    public function store($request)
    {
        if (isset($request['category']) && $request['category']) {

            $category = Category::where('name', $request['category'])
                ->where('invoice_id', $request['invoice_id'])
                ->first();

            if (!$category) {
                $category = Category::create([
                    'name' => $request['category'],
                    'invoice_id' => $request['invoice_id'],
                ]);
            }

            $request['category_id'] = $category->id;
        }

        return InvoiceItem::create($request);
    }

    public function create()
    {
        return $fields = Utils::getFields('invoice_items');
    }

    public function edit($id)
    {
        $invoiceItem =  InvoiceItem::find($id);
        $invoice_id = $invoiceItem->invoice_id;
        $invoiceItem =  [
            'label' => ['value' => $invoiceItem->label, 'type' => 'string'],
            'description' => ['value' => $invoiceItem->description, 'type' => 'string'],
            'unit_price' => ['value' => $invoiceItem->unit_price, 'type' => 'money'],
            'unit_cost' => ['value' => $invoiceItem->unit_cost, 'type' => 'money'],
            'unit_type' => ['value' => $invoiceItem->unit_type, 'type' => 'string'],
            'units' => ['value' => $invoiceItem->units, 'type' => 'number'],
            'provider_id' => ['value' => $invoiceItem->provider_id, 'type' => 'number'],
            'category' => ['value' => $invoiceItem->category?->name, 'type' => 'string'],
            'user_id' => ['value' => $invoiceItem->user_id, 'type' => 'number'],
        ];

        $fields = Utils::getFields('invoice_items', $invoice_id);
        return ["item" => $invoiceItem, "fields" => $fields];
    }

    public function update($id, $request)
    {
        $invoiceItem = InvoiceItem::find($id);

        if (isset($request['category']) && $request['category']) {

            $category = Category::where('name', $request['category'])
                ->where('invoice_id', $invoiceItem->invoice_id)
                ->first();

            if (!$category) {
                $category = Category::create([
                    'name' => $request['category'],
                    'invoice_id' => $invoiceItem->invoice_id,
                ]);
            }

            $request['category_id'] = $category->id;
        }

        $invoiceItem->update($request);
        return $invoiceItem;
    }

    public function delete($id)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->delete();
    }

    public function getById($id, $edit = false)
    {
        return  $invoiceItem = InvoiceItem::with(['files', 'notes', 'invoice'])->find($id);
    }

    public function getAll()
    {
        return InvoiceItem::all();
    }

    public function importCSV($request, $invoiceId)
    {

        $file = $request->file('file');

        $csv = Reader::createFromPath($file->getPathname(), 'r');

        $records = $csv->getRecords();
        $count = 0;
        $countSuccess = 0;
        $errors = [];
        $updatedRecords = [];

        foreach ($records as $record) {
            if ($count === 0) {
                $count++;
                continue;
            }

            $request = [
                'label' => $record[0],
                'unit_price' => $record[1],
                'units' => $record[2],
                'category' => $record[3],
                'unit_cost' => $record[4] ?? 0,
                'invoice_id' => $invoiceId,
            ];

            $validatedData = new ValidateDataService($request, $this->rules());
            $validatedData = $validatedData->getValidatedData();

            if ($validatedData['status']) {

                $category = Category::where('name', $request['category'])
                    ->where('invoice_id', $invoiceId)
                    ->first();

                if (!$category) {
                    $category = Category::create([
                        'name' => $request['category'],
                        'invoice_id' => $invoiceId,
                    ]);
                }

                $validatedData['data']['category_id'] = $category->id;

                $invoiceItem = InvoiceItem::where('label', $request['label'])
                    ->where('invoice_id', $invoiceId)
                    ->where('category_id', $category->id)
                    ->first();

                if ($invoiceItem) {
                    $invoiceItem->update($validatedData['data']);
                    $updatedRecords[] = ["cell" => $count + 1, "label" => $request['label']];
                } else {
                    $invoiceItem = InvoiceItem::create($validatedData['data']);
                    $countSuccess++;
                }
            } else {
                $errors[] = ["cell" => $count + 1, "label" => $request['label'], 'errors' => $validatedData['errors']];
            }

            $count++;
        }

        if ($errors) {
            return response()->json(
                ['message' => "Registros importados: $countSuccess. Se encontraron los siguientes errores", 'errors' => $errors, "updated" => $updatedRecords],
                422
            );
        } else {
            return response()->json(['message' => "importado con éxito, se crearon $countSuccess registros", "updated" => $updatedRecords], 200);
        }
    }
}
