<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Services\NoteService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class NoteController extends Controller
{
    protected $service;

    public function __construct(NoteService $noteService)
    {
        $this->middleware('can:read note', ['only' => ['index', 'show']]);
        $this->middleware('can:create note', ['only' => ['create', 'store']]);
        $this->middleware('can:edit note', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete note', ['only' => ['destroy']]);

        $this->service = $noteService;
        $this->rules = [
            'status' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function index(Request $request)
    {
        $notes = $this->service->getAll($request);
        return Inertia::render('Note/Notes', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'notes' => $notes,
        ]);
    }

    public function create()
    {
        return $this->service->getFields();
    }
    public function toggleStatus($id)
    {
        return $this->service->toggleStatus($id);
    }

    public function store(Request $request)
    {
        return $this->service->create($request);
    }

    public function show($id)
    {
        $note = $this->service->getById($id);
        return Inertia::render('Note/NoteDetail', [
            'note' => $note,
        ]);
    }

    public function edit($id)
    {
        $note = $this->service->getById($id, true);
        $fields = Utils::getFields('notes');

        return response()->json(["note" => $note, "fields" => $fields]);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
