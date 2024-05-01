<?php

namespace App\Services;

use App\Models\User;
use App\Utils\Utils;
use Illuminate\Http\Request;

class UserService
{
    public function store($request)
    {
        return User::create($request);
    }

    public function create()
    {
        $fields = Utils::getFields('users');
        return $fields;
    }

    public function edit($id)
    {
        $user = $this->getById($id);
        $fields = Utils::getFields('users');
        return ["item" => $fields, "fields" => $fields];
    }

    public function update($id, $request)
    {
        $user = User::find($id);
        $user->update($request);
        return $user;
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
            // Mapea aquí los campos de User que deseas obtener
            return [
                'id' => $user->id,
                'name' => $user->name,
                // Añade aquí los campos adicionales que desees obtener
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
                // Añade aquí los campos adicionales que desees obtener
            ];
        });

        return $users;
    }

    public function getUsers()
    {
        $users = User::all();

        $users->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                // Añade aquí los campos adicionales que desees obtener
            ];
        });

        return $users;
    }
}