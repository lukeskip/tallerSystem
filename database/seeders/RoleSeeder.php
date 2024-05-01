<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'superadmin','guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());
        
        $role = Role::create(['name' => 'admin','guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());
        $role->revokePermissionTo(['edit user','create user','delete user']);
     
        $role = Role::create(['name' => 'collaborator','guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());
        $role->revokePermissionTo(['edit user','create user','delete user']);
        $role->revokePermissionTo(['delete project']);
        $role->revokePermissionTo(['delete invoice']);
        $role->revokePermissionTo(['delete provider']);

        $role = Role::create(['name' => 'client','guard_name' => 'web']);
        
    }
}
