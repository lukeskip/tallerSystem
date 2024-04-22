<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Super Administrador',
            'slug' => 'super_admin',
        ]);
        
        Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
        ]);
        
        Role::create([
            'name' => 'Colaborador',
            'slug' => 'colaborator',
        ]);

        Role::create([
            'name' => 'Cliente',
            'slug' => 'client',
        ]);
        
    }
}
