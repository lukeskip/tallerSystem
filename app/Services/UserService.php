<?php

namespace App\Services;

use App\Models\User;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function store($request)
    {
        $role = Role::where('id',$request['role'])->first();
        $password = Str::random(10);
        $request['password'] = Hash::make($password);
        $user = User::create($request);

        $user->assignRole($role->name); 
        return $user; 
    }

    public function create()
    {
        $fields = Utils::getFields('users');
        return $fields;
    }

    public function edit($id)
    {
        $user =  User::find($id);
        $user =  [
            'name'=> ['value'=>$user->name,'type'=>'string'],
            'email'=> ['value'=>$user->email,'type'=>'string'],
            'role'=> ['value'=>$user->roles->first()->id,'type'=>'string'],
        ];
    
        $fields = Utils::getFields('users');
        return ["item" => $user, "fields" => $fields];
    }

    public function update($id, $request)
    {

        $roles = Role::where('id',$request['role'])->get();
        $user = User::find($id);
        $user->syncRoles($roles);
        return $user->update($request);    
    }

    public function delete($id)
    {
        $user = User::find($id);
        return $user->delete();
    }

    public function getById($id, $edit = false)
    {
        $user = User::find($id);

        if ($user) {
           
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        } else {
            return null;
        }
    }

    public function getAll($request = null)
    {
        $users = User::orderBy('id', 'desc');

        if ($request && $request->input('search')) {
            $users->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $users = $users->paginate();

        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()->name ?? '',
            ];
        });

        return $users;
    }

}