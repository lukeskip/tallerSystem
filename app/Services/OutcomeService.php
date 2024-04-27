<?php

namespace App\Services;

use App\Models\Outcome;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

class OutcomeService
{   
    public function create(){
        return $fields = Utils::getFields('outcomes');
    }

    public function store($request)
    {
        $income = Outcome::create($request);
    }

    public function update($id,$request)
    {
        $outcome->update($validatedData['data']);    
    }

    public function edit (){
        $income = $this->getById($id);
        $income = [
            'description'=> ['value'=>$income['description'],'type'=>'string'],
            'amount'=> ['value'=>$income['amount'],'type'=>'number'],
            'type'=> ['value'=>$income['type'],'type'=>'string'],
            'reference'=> ['value'=>$income['reference'],'type'=>'string'],
            'status'=> ['value'=>$income['status'],'type'=>'string'],
            'invoice_id'=> ['value'=>$income['invoice_id'],'type'=>'hidden'],
            'provider_id'=> ['value'=>$income['provider_id'],'type'=>'number'],
        ];
        $fields = Utils::getFields('incomes');
        return ["item"=>$income,"fields"=>$fields];
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

        if ($request &&  $request->input('month')) {
            $currentDate = Carbon::now();
            $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
            $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d H:i:s');

            $outcomes->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->orderBy('created_at','desc');
        }
        
        $outcomes = $outcomes->paginate();

        return $outcomes->map(function ($outcome) {
            return [
                'id' => $outcome->id,
                'description' => $outcome->description,
                'amount' => $outcome->amount,
                'type' => $outcome->type,
                'reference' => $outcome->reference,
                'status' => $outcome->status,
                'image' => $outcome->image,
                'invoice_id' => $outcome->invoice_id,
            ];
        });
    }

}
