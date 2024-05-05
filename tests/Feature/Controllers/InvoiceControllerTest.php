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
        $expectedFields = ['_token', 'status', 'iva','fee']; 
        $fieldsInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push($fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }
}
