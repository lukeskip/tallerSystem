<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use Illuminate\Http\Request;
use App\Services\OutcomeService;
use Inertia\Inertia;
use App\Utils\Utils;
use App\Services\ValidateDataService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class OutcomeController extends Controller
{
    protected $outcomeService;

    public function __construct(OutcomeService $outcomeService)
    {
        $this->middleware('can:read outcome', ['only' => ['index', 'show']]);
        $this->middleware('can:create outcome', ['only' => ['create', 'store']]);
        $this->middleware('can:edit outcome', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete outcome', ['only' => ['destroy']]);

        $this->service = $outcomeService;
        $this->rules = [
            'description' => 'nullable|string',
            'amount' => 'required|numeric|gt:0',
            'type' => 'required|string',
            'reference' => 'nullable|string',
            'image' => 'nullable',
            'status' => 'required|string',
            'invoice_id' => 'required|string',
            'provider_id' => 'required',
        ];
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
        return $this->service->create();
    }

    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($request->file('file')){
            $image = $request->file('file');
        
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'taller/outcomes',
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
                'folder' => 'taller/outcomes',
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
