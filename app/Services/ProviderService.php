<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;

class ProviderService
{
    public function create($request)
    {
        
        return $provider = Provider::create($request);  
        
    }

    public function edit ($id){
        $provider = $this->getById($id);

        $provider = [
                'name'=>['value'=>$provider['name'],'type'=>'string'],    
                'contact_name'=>['value'=>$provider['contact_name'],'type'=>'string'],    
                'phone'=>['value'=>$provider['phone'],'type'=>'string'],    
                'address'=>['value'=>$provider['address'],'type'=>'string'],    
                'email'=>['value'=>$provider['email'],'type'=>'string'],      
        ];
    
        $fields = Utils::getFields('providers');
        
        return ["item"=>$provider,"fields"=>$fields];
    }

    public function update(Provider $provider, array $data)
    {
        $provider->update($data);
        return $provider;    
    }

    public function delete($id)
    {   
        $provider = Provider::find($id);
        $provider->delete();
    }

    public function getById($id)
    {
        $provider =  Provider::find($id);

        if ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'contact_name' => $provider->contact_name,
                'phone' => $provider->phone,
                'address' => $provider->address,
                'email' => $provider->email,
            ];
        } else {
            return null;
        }
       
    }

    public function getAll($request = null)
    {
        $providers = Provider::orderBy('created_at','desc');

        if ($request && $request->input('search')) {
            $providers->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $providers = $providers->paginate();

        $providers->getCollection()->transform(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'contact_name' => $provider->contact_name,
                'phone' => $provider->phone,
                'address' => $provider->address,
                'email' => $provider->email,
            ];
        });

        return $providers;
    }

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            
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
