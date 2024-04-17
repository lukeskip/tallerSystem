<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Provider;

class ProviderService
{
    public function create(array $data)
    {
        return Provider::create($data);
    }

    public function update(Provider $provider, array $data)
    {
        $provider->update($data);
        return $provider;    
    }

    public function delete(Provider $provider)
    {
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
        $providers = Provider::query();

        if ($request && $request->input('search')) {
            $providers->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $providers = $providers->paginate(5);

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
}
