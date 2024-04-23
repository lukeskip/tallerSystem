<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Services\FileService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class FileController extends Controller
{
    public function __construct(FileService $fileService)
    {
        $this->service = $fileService;
    }

    public function index(Request $request)
    {
        $files = $this->service->getAll($request);
        return Inertia::render('File/Files', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'files' => $files,            
        ]);
    }

    public function create()
    {
        return $this->service->getFields();
    }

    public function store(Request $request)
    {
        return  $this->service->create($request);
    }

    public function show($id)
    {
        $file = $this->service->getById($id);
        return Inertia::render('File/FileDetail', [
            'file' => $file,
            
        ]);
    }

    public function edit($id)
    {
        $item = $this->service->getById($id,true);
        $fields = Utils::getFields('files');
        
        return response()->json(["item"=>$item,"fields"=>$fields]);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($id,$request);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}