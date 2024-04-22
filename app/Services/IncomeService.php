<?php

namespace App\Services;

use App\Models\Income;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class IncomeService
{

    public function create($request)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
           
            if($request->file('image')){
                $image = $request->file('image');
            
                $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'taller/incomes',
                    'resource_type' => 'image'])->getSecurePath();

                $validatedData['data']['image'] = $uploadedFileUrl;
            }

            return $income = Income::create($validatedData['data']);   
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function update(Income $income, array $data)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
            $income->update($validatedData['data']);
            return response()->json(['redirect' => 'income/'.$income->id]);   
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }      
    }

    public function delete($id)
    {
        $income = Income::find($id);
        return $income->delete();
    }

    public function getById($id)
    {
        $income = Income::find($id);

        if ($income) {
            return [
                'id' => $income->id,
                'description' => $income->description,
                'amount' => $income->amount,
                'type' => $income->type,
                'reference' => $income->reference,
                'invoice_id' => $income->invoice_id,
            ];
        } else {
            return null;
        }
    }

    public function getAll($request)
    {
        $incomes = Income::orderBy('id','desc');
        
        if ($request &&  $request->input('search')) {
            $incomes->where('description', 'like', '%' . $request->input('search') . '%');
        }
        
        $incomes = $incomes->paginate();

        return $incomes->getCollection()->transform(function ($income) {
            return [
                'id' => $income->id,
                'description' => $income->description,
                'amount' => $income->amount,
                'type' => $income->type,
                'reference' => $income->reference,
                'invoice_id' => $income->invoice_id,
            ];
        });
    }

    protected function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'reference' => 'string',
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
