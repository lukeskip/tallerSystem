<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Provider;
use App\Models\Outcome;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

class IncomeService
{
    public function create(){
        $fields = Utils::getFields('incomes');
        return response()->json($fields);
    }

    public function store($request)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
           
            if($request->file('file')){
                $image = $request->file('file');
            
                $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'taller/incomes',
                    'resource_type' => 'image'])->getSecurePath();

                $validatedData['data']['image'] = $uploadedFileUrl;
            }

            $income = Income::create($validatedData['data']);   
            
            $amountToPay = $income->amount * .60;
           
            $this->createOutcomesForProviders($income);
            
            
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function edit ($id){
        $income = $this->getById($id);
        $income = [
            'description'=> ['value'=>$income['description'],'type'=>'string'],
            'amount'=> ['value'=>$income['amount'],'type'=>'number'],
            'type'=> ['value'=>$income['type'],'type'=>'string'],
            'reference'=> ['value'=>$income['reference'],'type'=>'string'],
        ];
        $fields = Utils::getFields('incomes');
        return response()->json(["item"=>$income,"fields"=>$fields]);
    }

    public function update($id,$request)
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

        if ($request &&  $request->input('month')) {
            $currentDate = Carbon::now();
            $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
            $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d H:i:s');

            $incomes->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->orderBy('created_at','desc');
        }
        
        $incomes = $incomes->paginate();

        return $incomes->getCollection()->transform(function ($income) {
            return [
                'id' => $income->id,
                'description' => $income->description,
                'amount' => $income->amount,
                'type' => $income->type,
                'reference' => $income->reference,
                'image' => $income->image,
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


    protected function createOutcomesForProviders($income)
    {
        $providers = Provider::whereHas('invoiceItems',function ($query)use($income){
            $query->where('invoice_id',$income->invoice_id);
        })->with('invoiceItems','outcomes');
        
        if($providers->count()){
   
            $providers->each(function ($provider) use ($income) {
                $totalDebt = $provider->invoiceItems->sum('amount');
                $totalPaid = $provider->outcomes->where(function ($outcome) {
                    return $outcome->status === 'paid' || $outcome->status === 'pending';
                })->sum('amount');
                $balance = $totalDebt - $totalPaid;

                if($totalDebt > 0 ){
                    $percentageToPaid = ($balance / $totalDebt) * 100;
                }else{
                    $percentageToPaid = 0;
                }
                
                dump($balance);
                if($balance > 0){

                    if($percentageToPaid >= 50){
                        $amountToPayProvider = $totalDebt * .5;
                    }

                    
                    // Generates an outcome un pending
                    $provider->invoiceItems->each(function ($item) use (&$description,$amountToPayProvider) {
                        $description .= $item->label . ", ";
                    });
        
                    $outcome = Outcome::create([
                        'amount' => $amountToPayProvider,
                        'description' => $description,
                        'invoice_id' => $income->invoice_id,
                        'status' => 'pending',
                        'type' => 'pending',
                        'provider_id' => $provider->id
                    ]);
                }

            });
        }
        
    }
}
