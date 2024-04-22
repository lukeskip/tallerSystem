<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use Illuminate\Http\Request;
use App\Services\OutcomeService;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;

class OutcomeController extends Controller
{
    protected $outcomeService;

    public function __construct(OutcomeService $outcomeService)
    {
        $this->service = $outcomeService;
    }
    
    public function index(Request $request)
    {
        $outcomes = $this->service->getAll($request);
        
        return Inertia::render('Outcome/Outcomes', [
            'outcomes' => $outcomes,
        ]);
        
    }

    public function create()
    {
        $fields = Utils::getFields('outcomes');
        return response()->json($fields);
    }

    public function store(Request $request)
    {
        return $outcome = $this->service->create($request);
    }

    public function edit($id)
    {
        $outcome = $this->service->getById($id,true);
        $fields = Utils::getFields('outcomes');
        
        return response()->json(["item"=>$outcome,"fields"=>$fields]);
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
