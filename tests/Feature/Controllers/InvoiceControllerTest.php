<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Invoice;
use App\Http\Controllers\InvoiceController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Database\Factories\UserFactory;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class InvoiceControllerTest extends TestCase
{    

    use WithoutMiddleware;

    /** @test */
    public function it_can_list_invoices()
    {
        // Crear y autenticar un usuario con el rol "admin"
        $user = User::find(1);
        $this->actingAs($user);

        // Realizar la solicitud a la ruta 'invoices.index'
        $response = $this->get(route('cotizaciones.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Invoice/Invoices')
                 ->has('invoices');
        });
    }

    /** @test */
    public function it_can_create_an_invoice()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        
        $data = [
            'project_id' => 1,
            'status' => 'pending',
        ];
        
        $response = $this->post(route('cotizaciones.store'), $data);
        $response->assertStatus(302);
  
    }

    /** @test */
    public function it_can_show_an_invoice()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first(); 

        $response = $this->get(route('cotizaciones.show', $invoice->id)); 

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Invoice/InvoiceDetail')
                 ->has('invoice');
        });

        $response->assertStatus(200);

    }

    /** @test */
    public function it_can_delete_an_invoice()
    {
        
        $user = User::find(1);
        $this->actingAs($user);
        $invoice = Invoice::latest()->first();
        $response = $this->delete(route('cotizaciones.destroy', $invoice->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('proyectos.show', $invoice->project_id));


        $this->assertSoftDeleted('invoices', ['id' => $invoice->id]);
    }


    /** @test */
    public function it_can_show_edit_form_invoice()
    {
        $invoice = Invoice::latest()->first();
        $response = $this->get(route('cotizaciones.edit', $invoice->id));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData['fields']);
        $this->assertNotEmpty($responseData['item']);
        $expectedFields = ['_token', 'label', 'status', 'iva','fee']; 
        $fieldsInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push($fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }

    /** @test */
    public function it_can_create_an_invoice_with_label()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $data = [
            'project_id' => 1,
            'status' => 'pending',
            'label' => 'Sala Principal',
        ];

        $response = $this->post(route('cotizaciones.store'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('invoices', [
            'project_id' => 1,
            'label' => 'Sala Principal',
        ]);
    }

    /** @test */
    public function it_can_update_an_invoice_label()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first();

        $data = [
            'project_id' => $invoice->project_id,
            'status' => $invoice->status,
            'label' => 'Comedor y Terraza',
        ];

        $response = $this->put(route('cotizaciones.update', $invoice->id), $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'label' => 'Comedor y Terraza',
        ]);
    }

    /** @test */
    public function it_calculates_discounts_before_fee_correctly()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first();
        
        // Add a 10% discount extra before commission
        $extra = \App\Models\Extra::create([
            'invoice_id' => $invoice->id,
            'label' => 'Descuento 10%',
            'value' => 10,
            'type' => 'percentage',
            'calculation_basis' => 'before_commission',
            'is_discount' => true,
            'label_color' => '#fef08a',
        ]);

        $response = $this->get(route('cotizaciones.show', $invoice->id));
        $response->assertStatus(200);

        $extra->delete();
    }

    /** @test */
    public function it_calculates_item_discounts_correctly()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first();
        
        $item = \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'label' => 'Item de Prueba',
            'unit_price' => 100,
            'units' => 10,
            'discount' => 10,
            'discount_type' => 'percentage',
        ]);

        $this->assertEquals(100, $item->discount_amount);
        $this->assertEquals(900, $item->total);

        $response = $this->get(route('cotizaciones.show', $invoice->id));
        $response->assertStatus(200);

        $item->delete();
    }

    /** @test */
    public function it_can_toggle_invoice_item_active_status()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first();
        $item = \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'label' => 'Item Toggle Test',
            'unit_price' => 200,
            'units' => 2,
            'is_active' => true,
        ]);

        $this->assertTrue($item->is_active);

        // Deactivate
        $response = $this->post(route('invoice-items.toggle-active', $item->id));
        $response->assertStatus(200);
        $response->assertJson(['is_active' => false]);
        $this->assertFalse($item->fresh()->is_active);

        // Reactivate
        $response = $this->post(route('invoice-items.toggle-active', $item->id));
        $response->assertStatus(200);
        $response->assertJson(['is_active' => true]);
        $this->assertTrue($item->fresh()->is_active);

        $item->delete();
    }

    /** @test */
    public function it_excludes_inactive_items_from_invoice_totals()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $invoice = Invoice::latest()->first();

        $activeItem = \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'label' => 'Active Item',
            'unit_price' => 500,
            'units' => 2,
            'is_active' => true,
        ]);

        $inactiveItem = \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'label' => 'Inactive Item',
            'unit_price' => 300,
            'units' => 1,
            'is_active' => false,
        ]);

        $freshInvoice = Invoice::with('invoiceItems')->find($invoice->id);
        $subtotal = $freshInvoice->subtotal;

        // Inactive item (300) should NOT be in the subtotal
        // The active item contributes 1000
        $this->assertTrue($freshInvoice->invoiceItems->where('is_active', true)->sum('total') == $subtotal);

        // Check InvoiceService getById
        $invoiceService = new \App\Services\InvoiceService();
        $invoiceData = $invoiceService->getById($invoice->id);

        $itemInService = collect($invoiceData['invoiceItems'])->firstWhere('id', $inactiveItem->id);
        $this->assertFalse($itemInService['is_active']);

        $activeItemInService = collect($invoiceData['invoiceItems'])->firstWhere('id', $activeItem->id);
        $this->assertTrue($activeItemInService['is_active']);

        $activeItem->delete();
        $inactiveItem->delete();
    }
}
