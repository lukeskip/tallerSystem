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
        $user = User::create([
            'name' => 'Sergio García',
            'email' => 'contacto@chekogarcia.com.mx',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $user->assignRole('superadmin');

        $user = User::create([
            'name' => 'Lorena Marín',
            'email' => 'lorena@somainteriores.com.mx',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $user->assignRole('superadmin');
    }
}
