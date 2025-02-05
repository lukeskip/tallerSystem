<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Category; // Importar el modelo de categoría

class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $data = [
            [
                'label' => 'Tapete Sala 3x4',
                'description' => 'Tapete para la sala de dimensiones 3x4',
                'unit_price' => 29881,
                'units' => 1,
                'category' => 'Sala',
            ],
            [
                'label' => 'Tapete Comedor 3x4',
                'description' => 'Tapete para el comedor de dimensiones 3x4',
                'unit_price' => 29881,
                'units' => 1,
                'category' => 'Comedor',
            ],
            [
                'label' => 'Tapete recámara principal Latur 3 x 4',
                'description' => 'Tapete para la recámara principal de dimensiones 3x4',
                'unit_price' => 28652,
                'units' => 2,
                'category' => 'Recámara',
            ],
            [
                'label' => 'Tapete sala de tv 2.40 x 3.30 Bali',
                'description' => 'Tapete para la sala de TV de dimensiones 2.40x3.30 Bali',
                'unit_price' => 15706,
                'units' => 1,
                'category' => 'Sala de TV',
            ],
            [
                'label' => 'Sillón desigual sala de tv',
                'description' => 'Sillón desigual para la sala de TV',
                'unit_price' => 157296,
                'units' => 1,
                'category' => 'Sala de TV',
            ],
            [
                'label' => 'giulo set de 2 mesas 80x80x32 y 50x50x50',
                'description' => 'giulo set de 2 mesas con dimensiones 80x80x32 y 50x50x50',
                'unit_price' => 63800,
                'units' => 1,
                'category' => 'Comedor',
            ],
            [
                'label' => 'Lámpara globo',
                'description' => 'Lámpara en forma de globo',
                'unit_price' => 6740,
                'units' => 2,
                'category' => 'Sala',
            ],
        ];

        // Obtener todas las facturas
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            if ($invoice->exists()) {
                foreach ($data as $item) {
                    if ($faker->boolean(70)) {
                        // Buscar o crear la categoría sin `invoice_id`
                        $category = Category::firstOrCreate(
                            ['name' => $item['category'], 'invoice_id' => $invoice->id]
                        );

                        // Crear el InvoiceItem con la relación correcta
                        InvoiceItem::create([
                            'label' => $item['label'],
                            'description' => $item['description'],
                            'unit_price' => $item['unit_price'],
                            'units' => $item['units'],
                            'comission' => $invoice->project->comission,
                            'category_id' => $category->id, // Relación con la categoría
                            'invoice_id' => $invoice->id,
                        ]);
                    }
                }
            }
        }
    }
}
