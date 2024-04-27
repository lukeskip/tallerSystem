<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Inertia\Inertia;
use App\Utils\Utils;
use Illuminate\Support\Facades\Route;
use App\Services\ValidateDataService;

class ProjectController extends Controller
{
    public function __construct(ProjectService $projectService)
    {
        $this->service = $projectService;
        $this->rules = [
            'name' => 'required|string',
            'address' => 'required|string',
            'comission' => 'required|numeric|gt:0',
            'client_id'=> 'numeric|gt:0'
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        $projects = $this->service->getAll($request);
        return Inertia::render('Project/Projects', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'projects' => $projects,            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = Utils::getFields('projects');
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            return $item = $this->service->store($validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = $this->service->getById($id);
        return Inertia::render('Project/ProjectDetail', [
            'project' => $project,
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fields = $this->service->edit($id);
        return response()->json($fields);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            $item = $this->service->update($id,$validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $project = $this->service->delete($id);
    }
}
