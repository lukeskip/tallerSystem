<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected ProjectService $service;

    public function __construct(ProjectService $projectService)
    {
        $this->service = $projectService;
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = $this->service->getAll($request);
        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    /**
     * Display the specified project.
     */
    public function show(int|string $id): JsonResponse
    {
        $project = $this->service->getById($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }
}
