<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Provider;
use App\Models\Outcome;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use Carbon\Carbon;

class IncomeService
{
    

    public function create(){
        return $fields = Utils::getFields('incomes');
    }

    public function store($request)
    {

        $income = Income::create($request);   
        
        $amountToPay = $income->amount * .60;
        
        $this->createOutcomesForProviders($income);
    }

    public function edit ($id){
        $income = $this->getById($id);
        $income = [
            'description'=> ['value'=>$income['description'],'type'=>'string'],
            'amount'=> ['value'=>$income['amount'],'type'=>'number'],
            'type'=> ['value'=>$income['type'],'type'=>'string'],
            'reference'=> ['value'=>$income['reference'],'type'=>'string'],
            'invoice_id'=> ['value'=>$income['invoice_id'],'type'=>'hidden'],
        ];
        $fields = Utils::getFields('incomes');
        return ["item"=>$income,"fields"=>$fields];
    }

    public function update($id,$request)
    {
        $income = Income::find($id);
        $income->update($request);
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
