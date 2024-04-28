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

        $data = [
            [
                'label' => 'Tapete Sala 3x4',
                'description' => 'Tapete para la sala de dimensiones 3x4',
                'unit_price' => '29881',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Tapete Comedor 3x4',
                'description' => 'Tapete para el comedor de dimensiones 3x4',
                'unit_price' => '29881',
                'units' => 1,
                'category' => 'Comedor',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Tapete recámara principal Latur 3 x 4',
                'description' => 'Tapete para la recámara principal de dimensiones 3x4',
                'unit_price' => '28652',
                'units' => 2,
                'category' => 'Recámara',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Tapete sala de tv 2.40 x 3.30 Bali',
                'description' => 'Tapete para la sala de TV de dimensiones 2.40x3.30 Bali',
                'unit_price' => '15706',
                'units' => 1,
                'category' => 'Sala de TV',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Sillón desigual sala de tv',
                'description' => 'Sillón desigual para la sala de TV',
                'unit_price' => '157296',
                'units' => 1,
                'category' => 'Sala de TV',
                'invoice_id' => 1,
            ],
            [
                'label' => 'giulo set de 2 mesas 80x80x32 y 50x50x50',
                'description' => 'giulo set de 2 mesas con dimensiones 80x80x32 y 50x50x50',
                'unit_price' => '63800',
                'units' => 1,
                'category' => 'Comedor',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Lámpara globo',
                'description' => 'Lámpara en forma de globo',
                'unit_price' => '6740',
                'units' => 2,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Mesa de centro',
                'description' => 'Mesa para el centro de la sala',
                'unit_price' => '14500',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Juego de sábanas 700 hilos con bordado al tono',
                'description' => 'Juego de sábanas de 700 hilos con bordado al tono',
                'unit_price' => '8119',
                'units' => 2,
                'category' => 'Recámara',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Juego de Cama',
                'description' => 'Juego de cama',
                'unit_price' => '11112',
                'units' => 1,
                'category' => 'Recámara',
                'invoice_id' => 1,
            ],
            [
                'label' => '2 Cojines 60 x 60',
                'description' => 'Dos cojines de tamaño 60x60',
                'unit_price' => '3478',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => '2 cojines de 50 x 50',
                'description' => 'Dos cojines de tamaño 50x50',
                'unit_price' => '2378',
                'units' => 1,
                'category' => 'Recámara',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Sala Mikele Tela B topo',
                'description' => 'Sala de tela B topo modelo Mikele',
                'unit_price' => '279128',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Sillón P05 olive',
                'description' => 'Sillón modelo P05 color olive',
                'unit_price' => '41740',
                'units' => 2,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Mesa lateral mármol',
                'description' => 'Mesa lateral de mármol',
                'unit_price' => '23343',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Sala Modular exterior',
                'description' => 'Sala modular para exteriores',
                'unit_price' => '179460',
                'units' => 1,
                'category' => 'Exterior',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Puff Lounge bergen Cream',
                'description' => 'Puff Lounge color Cream',
                'unit_price' => '82620',
                'units' => 1,
                'category' => 'Sala',
                'invoice_id' => 1,
            ],
            [
                'label' => 'Bufetero Alfonso Marina 1.69 x 60 x 90',
                'description' => 'Bufetero Alfonso Marina de dimensiones 1.69x60x90',
                'unit_price' => '168392',
                'units' => 1,
                'category' => 'Comedor',
                'invoice_id' => 1,
            ],
        ];
        
        

        // Obtener todas las facturas
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            if ($invoice->exists()) {

                foreach ($data as $item) {
                    InvoiceItem::create([
                        'label' => $item['label'],
                        'description' => $item['description'],
                        'unit_price' => $item['unit_price'],
                        'units' => $item['units'],
                        'comission' => $invoice->project->comission,
                        'category' => $item['category'],
                        'invoice_id' => $invoice->id,
                    ]);
                }
            }
        }
    }
}
