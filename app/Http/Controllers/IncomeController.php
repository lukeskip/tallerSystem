<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Services\IncomeService;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;

class IncomeController extends Controller
{
    protected $incomeService;

    public function __construct(IncomeService $incomeService)
    {
        $this->service = $incomeService;
    }
    
    public function index(Request $request)
    {
        $incomes = $this->service->getAll($request);
        
        return Inertia::render('Income/Incomes', [
            'incomes' => $incomes,
        ]);
        
    }

    public function create()
    {
        $fields = Utils::getFields('incomes');
        return response()->json($fields);
    }

    public function store(Request $request)
    {
        return $income = $this->service->create($request);
    }

    public function edit($id)
    {
        $income = $this->service->getById($id,true);
        $fields = Utils::getFields('income');
        
        return response()->json(["item"=>$income,"fields"=>$fields]);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($id,$request);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
    }
}
