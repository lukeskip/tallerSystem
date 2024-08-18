<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\File;
use App\Models\Project;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia; 
use App\Utils\Utils;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;


class FileService
{

    public function getFields(){
        $fields = Utils::getFields('files');
        return response()->json($fields);
    }

    public function create($request)
    {   
        if($request->file('file')){

            try {
           
                $file = $request->file('file');
    
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
    
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'taller/files',
                    'resource_type'=> "auto"
                ]);
    
              
                $request['url'] = $uploadedFileUrl->getSecurePath();
                $request['public_id'] = $uploadedFileUrl->getPublicId();
                $request['name'] = $fileName;
                $request['extension'] = $extension;
                

            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $file =new File;
            $file->url =$validatedData['data']['url'];
            $file->public_id =$validatedData['data']['public_id'];
            $file->name =$validatedData['data']['name'];
            $file->extension =$validatedData['data']['extension'];
            $file->save();
            
            if(!empty($validatedData['data']['project_id'])){
                $project = Project::find($validatedData['data']['project_id']);
                $project->files()->attach($file->id);
            }
            
            if(!empty($validatedData['data']['invoice_item_id'])){
                $InvoiceItem = InvoiceItem::find($validatedData['data']['invoice_item_id']);
                $InvoiceItem->files()->attach($file->id);
            }

            return $file;
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
        
    }

    public function update($id, $request)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $file = File::find($id);
           return  $file->update($validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }  
    }

    public function delete($id)
    {
        try {
            $file = File::find($id);
            $cloudinary = Cloudinary::destroy($file->public_id,["resource_type" => 'image' ]); 
            return $file->delete(); 
        } catch (\Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
              
        
    }

    public function getById($id,$edit=false)
    {
        return $file =  File::find($id);
    }

    public function getAll($request = null)
    {
       
        $files = File::orderBy('id','desc');

        $files = $files->paginate();
        
        $files->getCollection()->transform(function ($file) {
            return [
                'id' => $file->id,
                'project_id' => $file->project_id,
                'url' => $file->url,
                'extension' => $file->extension,
                'format_date'=> $file->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return $files;
    }

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'url' => 'required|string',
            'name' => 'required|string',
            'extension' => 'required|string',
            'public_id' => 'required|string',
            'project_id' => 'nullable',
            'invoice_item_id' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }

            return ['status'=>false,'errors'=>$fieldErrors];
        }

        $cleanedData = $validator->validated();

        return ['status'=>true,'data'=>$cleanedData];
    }
}
