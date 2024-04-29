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
        
        return $this->createOutcomesForProviders($income);
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
        
        return $this->createOutcomesForProviders($income);
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
        if($income->outcomes){
            $income->outcomes()->where('status', 'pending')->delete();
        }

        $providers = Provider::whereHas('invoiceItems',function ($query)use($income){
            $query->where('invoice_id',$income->invoice_id);
        })->with('invoiceItems','outcomes')->get();
        
        if($providers->count()){
            $totalDebt = $providers->flatMap->invoiceItems->sum('amount');

            $providers->each(function ($provider) use ($income,$totalDebt) {
                $totalDebtProvider = $provider->invoiceItems->sum('amount');
                $totalPaidProvider = $provider->outcomes->where(function ($outcome) {
                    return $outcome->status === 'paid' || $outcome->status === 'pending';
                })->sum('amount');

                
                $balanceProvider = $totalDebtProvider - $totalPaidProvider;
                $proportion = ($balanceProvider * 100) /  $totalDebt;
                
                if($balanceProvider > 0){


                    $amountToPayProvider = $income->amount * ($proportion / 100);

                    $provider->invoiceItems->each(function ($item) use (&$description) {
                        $description .= $item->label . ", ";
                    });

        
                    $outcome = Outcome::create([
                        'amount' => $amountToPayProvider,
                        'description' => $description,
                        'invoice_id' => $income->invoice_id,
                        'status' => 'pending',
                        'type' => 'pending',
                        'provider_id' => $provider->id,
                        'income_id' => $income->id
                    ]);
               
                }

            });
        }
        
    }
}
