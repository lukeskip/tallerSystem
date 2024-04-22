<?php

namespace App\Services;

use App\Models\Outcome;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;

class OutcomeService
{

    public function create($request)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
            return $outcome = Outcome::create($validatedData['data']);   
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function update(Outcome $outcome, array $data)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
            $outcome->update($validatedData['data']);
            return response()->json(['redirect' => 'outcome/'.$outcome->id]);   
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }      
    }

    public function delete($id)
    {
        $income = Outcome::find($id);
        return $income->delete();
    }

    public function getById($id)
    {
        $outcome = Outcome::find($id);

        if ($outcome) {
            return [
                'id' => $outcome->id,
                'description' => $outcome->description,
                'amount' => $outcome->amount,
                'type' => $outcome->type,
                'reference' => $outcome->reference,
                'image' => $outcome->image,
                'invoice_id' => $outcome->invoice_id,
            ];
        } else {
            return null;
        }
    }

    public function getAll($request)
    {

        $outcomes = Outcome::orderBy('id','desc');
        
        if ($request &&  $request->input('search')) {
            $outcomes->where('description', 'like', '%' . $request->input('search') . '%');
        }
        
        $outcomes = $outcomes->paginate();

        return $outcomes->map(function ($outcome) {
            return [
                'id' => $outcome->id,
                'description' => $outcome->description,
                'amount' => $outcome->amount,
                'type' => $outcome->type,
                'reference' => $outcome->reference,
                'image' => $outcome->image,
                'invoice_id' => $outcome->invoice_id,
            ];
        });
    }

    protected function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'type' => 'string',
            'reference' => 'string',
            'image' => 'nullable|string',
            'status' => 'required|string',
            'invoice_id' => 'required|string', // Puedes ajustar esta validación según tus necesidades
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }

            return ['status' => false, 'errors' => $fieldErrors];
        }

        $cleanedData = $validator->validated();

        return ['status' => true, 'data' => $cleanedData];
    }
}
