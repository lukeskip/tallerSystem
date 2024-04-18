<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Invoice;
use App\Models\InvoiceItem;


class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Obtener todas las facturas
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            if ($invoice->exists()) {
                // Generar un número aleatorio entre 5 y 10 para la cantidad de InvoiceItems
                $numItems = $faker->numberBetween(5, 10);
        
                // Crear InvoiceItems
                for ($i = 0; $i < $numItems; $i++) {
                    InvoiceItem::create([
                        'label' => $faker->sentence(3),
                        'description' => $faker->paragraph,
                        'comission' => $faker->randomFloat(2, 0, 100),
                        'unit_type' => $faker->randomElement(['pieza', 'kg', 'l', 'm']),
                        'unit_price' => $faker->randomFloat(2, 10, 1000),
                        'units' => $faker->numberBetween(1, 6),
                        'invoice_id' => $invoice->id,
                    ]);
                }
            }
        }
    }
}
