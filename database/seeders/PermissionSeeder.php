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
        Permission::firstOrCreate(['name' => 'create user','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read user','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit user','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete user','guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create client','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read client','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit client','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete client','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create project','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read project','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit project','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete project','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create invoice','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read invoice','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit invoice','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete invoice','guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create note','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read note','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit note','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete note','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create invoice_item','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read invoice_item','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit invoice_item','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete invoice_item','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create income','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read income','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit income','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete income','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create outcome','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read outcome','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit outcome','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete outcome','guard_name' => 'web']);

        
        Permission::firstOrCreate(['name' => 'create provider','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read provider','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit provider','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete provider','guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create category','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read category','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit category','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete category','guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create order','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read order','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit order','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete order','guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create fabric','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read fabric','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit fabric','guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete fabric','guard_name' => 'web']);
        
    }
}
