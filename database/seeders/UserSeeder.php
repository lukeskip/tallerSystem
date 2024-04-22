<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'Sergio García',
            'email' => 'contacto@chekogarcia.com.mx',
            'password' => bcrypt('willy188'),
        ]);
        
    }
}
