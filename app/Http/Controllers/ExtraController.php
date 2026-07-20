<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use Illuminate\Http\Request;
use App\Services\ValidateDataService;
use App\Utils\Utils;

class ExtraController extends Controller
{
    private $rules;

    public function __construct()
    {
        $this->middleware('auth');
        $this->rules = [
            'invoice_id' => 'required|string',
            'label' => 'required|string|max:255',
            'value' => 'required|numeric',
            'type' => 'required|string|in:percentage,fixed',
            'calculation_basis' => 'required|string|in:before_commission,after_commission',
        ];
    }

    public function create(Request $request)
    {
        $fields = Utils::getFields('invoice_extras');
        return response()->json($fields);
    }

    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            $extra = Extra::create($validatedData['data']);
            return response()->json(['message' => 'Creado con éxito', 'item' => $extra]);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function edit($id)
    {
        $extra = Extra::find($id);
        $extraData = [
            'label' => ['value' => $extra->label, 'type' => 'string'],
            'value' => ['value' => $extra->value, 'type' => 'number'],
            'type' => ['value' => $extra->type, 'type' => 'select'],
            'calculation_basis' => ['value' => $extra->calculation_basis, 'type' => 'select'],
        ];

        $fields = Utils::getFields('invoice_extras');
        return response()->json(['item' => $extraData, 'fields' => $fields]);
    }

    public function update(Request $request, $id)
    {
        $extra = Extra::find($id);
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            $extra->update($validatedData['data']);
            return response()->json(['message' => 'Actualizado con éxito', 'item' => $extra]);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function destroy($id)
    {
        $extra = Extra::find($id);
        $extra->delete();
        return response()->json(['message' => 'Eliminado con éxito']);
    }
}
