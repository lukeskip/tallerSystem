<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Client;
use App\Http\Controllers\ClientController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Database\Factories\UserFactory;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ClientControllerTest extends TestCase
{    

    use WithoutMiddleware;

    /** @test */
    public function it_can_list_clients()
    {
        // Crear y autenticar un usuario con el rol "admin"
        $user = UserFactory::new()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        // Realizar la solicitud a la ruta 'clientes.index'
        $response = $this->get(route('clientes.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Client/Clients')
                 ->has('clients');
        });
    }

    /** @test */
    public function it_can_create_a_client()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $data = [
            'name' => 'Test Client',
            'contact_name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'address' => 'Calzada de la virgen 3000',
        ];

        $response = $this->post(route('clientes.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', ['name' => 'Test Client']);
    }

    /** @test */
    public function it_can_show_a_client()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get(route('clientes.show', 1)); 

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Client/ClientDetail')
                 ->has('client');
        });

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', ['name' => 'Test Client']);
    }

    /** @test */
    public function it_can_delete_a_client()
    {
        
        $user = User::find(1);
        $this->actingAs($user);
        $client = Client::where('name', 'Test Client')->first(); 
        $response = $this->delete(route('clientes.destroy', $client->id));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_it_can_show_create_form_client()
    {
        
        $response = $this->get(route('clientes.create'));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData);
        $expectedFields = ['_token', 'name', 'address', 'contact_name', 'phone','address'];
        $fielsdInResponse = [];
        foreach ($responseData as $field) {
            array_push( $fielsdInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields,$fielsdInResponse);
        $this->assertEmpty($diff);
    }

    public function test_it_can_show_edit_form_client()
    {
        $client = Client::find(1);
        $response = $this->get(route('clientes.edit',$client->id));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData['fields']);
        $this->assertNotEmpty($responseData['item']);
        $expectedFields = ['_token', 'name', 'address', 'contact_name', 'phone','address'];
        $fielsdInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push( $fielsdInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields,$fielsdInResponse);
        $this->assertEmpty($diff);
    }
    
 
}
