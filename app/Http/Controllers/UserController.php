<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\ValidateDataService;
use Inertia\Inertia;
use App\Utils\Utils;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $userService)
    {
        $this->middleware('can:read user', ['only' => ['index', 'show']]);
        $this->middleware('can:create user', ['only' => ['create', 'store']]);
        $this->middleware('can:edit user', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete user', ['only' => ['destroy']]);

        $this->service = $userService;
        $this->rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $this->service->getAll($request);
        return Inertia::render('User/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = $this->service->create();
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if ($validatedData['status']) {
            return $item = $this->service->store($validatedData['data']);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = $this->service->getById($id);
        return Inertia::render('User/UserDetail', [
            'user' => $user,
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

        if ($validatedData['status']) {
            $item = $this->service->update($id, $validatedData['data']);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       return  $this->service->delete($id);
    }
}
