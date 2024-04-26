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
        return $this->service->create();
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function edit($id)
    {
        return $this->service->edit($id);
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
