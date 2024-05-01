<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\InvoiceItemSeeder;
use Database\Seeders\InvoiceSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ProviderSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(InvoiceItemSeeder::class);
    }
}
