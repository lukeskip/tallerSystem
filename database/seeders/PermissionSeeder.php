<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create user','guard_name' => 'web']);
        Permission::create(['name' => 'read user','guard_name' => 'web']);
        Permission::create(['name' => 'edit user','guard_name' => 'web']);
        Permission::create(['name' => 'delete user','guard_name' => 'web']);

        Permission::create(['name' => 'create client','guard_name' => 'web']);
        Permission::create(['name' => 'read client','guard_name' => 'web']);
        Permission::create(['name' => 'edit client','guard_name' => 'web']);
        Permission::create(['name' => 'delete client','guard_name' => 'web']);

        
        Permission::create(['name' => 'create project','guard_name' => 'web']);
        Permission::create(['name' => 'read project','guard_name' => 'web']);
        Permission::create(['name' => 'edit project','guard_name' => 'web']);
        Permission::create(['name' => 'delete project','guard_name' => 'web']);

        
        Permission::create(['name' => 'create invoice','guard_name' => 'web']);
        Permission::create(['name' => 'read invoice','guard_name' => 'web']);
        Permission::create(['name' => 'edit invoice','guard_name' => 'web']);
        Permission::create(['name' => 'delete invoice','guard_name' => 'web']);

        Permission::create(['name' => 'create note','guard_name' => 'web']);
        Permission::create(['name' => 'read note','guard_name' => 'web']);
        Permission::create(['name' => 'edit note','guard_name' => 'web']);
        Permission::create(['name' => 'delete note','guard_name' => 'web']);

        
        Permission::create(['name' => 'create invoice_item','guard_name' => 'web']);
        Permission::create(['name' => 'read invoice_item','guard_name' => 'web']);
        Permission::create(['name' => 'edit invoice_item','guard_name' => 'web']);
        Permission::create(['name' => 'delete invoice_item','guard_name' => 'web']);

        
        Permission::create(['name' => 'create income','guard_name' => 'web']);
        Permission::create(['name' => 'read income','guard_name' => 'web']);
        Permission::create(['name' => 'edit income','guard_name' => 'web']);
        Permission::create(['name' => 'delete income','guard_name' => 'web']);

        
        Permission::create(['name' => 'create outcome','guard_name' => 'web']);
        Permission::create(['name' => 'read outcome','guard_name' => 'web']);
        Permission::create(['name' => 'edit outcome','guard_name' => 'web']);
        Permission::create(['name' => 'delete outcome','guard_name' => 'web']);

        
        Permission::create(['name' => 'create provider','guard_name' => 'web']);
        Permission::create(['name' => 'read provider','guard_name' => 'web']);
        Permission::create(['name' => 'edit provider','guard_name' => 'web']);
        Permission::create(['name' => 'delete provider','guard_name' => 'web']);
        
    }
}
