<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Inertia\Inertia;
use App\Utils\Utils;

class ProjectController extends Controller
{
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        $projects = $this->projectService->getAll($request);
        return Inertia::render('Project/Projects', [
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = $this->projectService->getById($id);
        return Inertia::render('Project/ProjectDetail', [
            'project' => $project,
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = $this->projectService->delete($id);
    }
}
