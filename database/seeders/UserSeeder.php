<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Sergio García',
            'email' => 'contacto@chekogarcia.com.mx',
            'password' => bcrypt('willy188'),
            'role_id'=>1
        ]);
        
    }
}
