<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Services\IncomeService;
use Inertia\Inertia;
use App\Utils\Utils;
use App\Services\ValidateDataService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class IncomeController extends Controller
{
    protected $incomeService;

    public function __construct(IncomeService $incomeService)
    {
        $this->middleware('can:read income', ['only' => ['index', 'show']]);
        $this->middleware('can:create income', ['only' => ['create', 'store']]);
        $this->middleware('can:edit income', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete income', ['only' => ['destroy']]);

        $this->service = $incomeService;
        $this->rules = [
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'reference' => 'nullable|string',
            'invoice_id' => 'required|string',
        ];
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
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($request->file('file')){
            $image = $request->file('file');
        
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'taller/incomes',
                'resource_type' => 'image'])->getSecurePath();

            $validatedData['data']['image'] = $uploadedFileUrl;
        }

        if($validatedData['status']){
            return $item = $this->service->store($validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
    }

    public function edit($id)
    {
        return $this->service->edit($id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($request->file('file')){
            $image = $request->file('file');
        
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'taller/incomes',
                'resource_type' => 'image'])->getSecurePath();

            $validatedData['data']['image'] = $uploadedFileUrl;
        }

        if($validatedData['status']){
            $item = $this->service->update($id,$validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);
    }
}
