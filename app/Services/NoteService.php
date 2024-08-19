<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Note;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia; 
use App\Utils\Utils;

class NoteService
{
    public function getFields()
    {
        $fields = Utils::getFields('notes');
        return response()->json($fields);
    }

    public function toggleStatus($id)
    {
        $note = $this->getById($id);

        if($note->status === "completed"){
            $note->status = "pending";
        }else if($note->status === "pending"){
            $note->status = "completed";
        }

        $note->save();

        return response()->json($note);
    }

    public function create($request)
    {   
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
            $note = new Note;
            $note->content = $validatedData['data']['content'];
            $note->status = $validatedData['data']['status'];
            $note->save();

            $note->invoiceItems()->attach($validatedData['data']['invoice_item_id']);
            
            return $note;
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }
    }

    public function update($id, $request)
    {
        $validatedData = $this->validateData($request);
        
        if ($validatedData['status']) {
            $note = Note::find($id);
            return $note->update($validatedData['data']);
        } else {
            return response()->json(['errors' => $validatedData['errors']], 422);
        }  
    }

    public function delete($id)
    {
        try {
            $note = Note::find($id);
            return $note->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getById($id, $edit = false)
    {
        return Note::find($id);
    }

    public function getAll($request = null)
    {
        $notes = Note::orderBy('id', 'desc');
        $notes = $notes->paginate();

        $notes->getCollection()->transform(function ($note) {
            return [
                'id' => $note->id,
                'content' => $note->content,
                'format_date' => $note->format_date,
            ];
        });

        return $notes;
    }

    protected function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'status' => 'required|string',
            'invoice_item_id' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }

            return ['status' => false, 'errors' => $fieldErrors];
        }

        $cleanedData = $validator->validated();

        return ['status' => true, 'data' => $cleanedData];
    }
}
