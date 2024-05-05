<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Provider;
use App\Http\Controllers\ProviderController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Database\Factories\UserFactory; 
use Inertia\Testing\AssertableInertia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProviderControllerTest extends TestCase
{    

    use WithoutMiddleware;

    /** @test */
    public function it_can_list_providers()
    {
        // Crear y autenticar un usuario con el rol "admin"
        $user = UserFactory::new()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        // Realizar la solicitud a la ruta 'proveedores.index'
        $response = $this->get(route('proveedores.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Provider/Providers')
                 ->has('providers');
        });
    }

    /** @test */
    public function it_can_create_a_provider()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $data = [
            'name' => 'Test Provider',
            'contact_name' => 'Test Contact',
            'phone' => '123456789',
            'address' => 'Test Address',
            'email' => 'test@example.com',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('proveedores.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('providers', ['name' => 'Test Provider']);
    }

    /** @test */
    public function it_can_show_a_provider()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get(route('proveedores.show',1));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Provider/ProviderDetail')
                 ->has('provider');
        });

        $response->assertStatus(200);

        $this->assertDatabaseHas('providers', ['name' => 'Test Provider']);
    }

    /** @test */
    public function it_can_delete_a_provider()
    {
        
        $user = User::find(1);
        $this->actingAs($user);
        $provider = Provider::where('name','Test Provider')->first();
        $response = $this->delete(route('proveedores.destroy',$provider->id));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('providers', ['id' => $provider->id]);
    }
    
    public function test_it_can_show_create_form_provider()
    {
        
        $response = $this->get(route('proveedores.create'));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData);
        $expectedFields = ['_token', 'name', 'contact_name', 'phone', 'address', 'email'];
        $fieldsInResponse = [];
        foreach ($responseData as $field) {
            array_push( $fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }

    public function test_it_can_show_edit_form_provider()
    {
        $provider = Provider::find(1);
        $response = $this->get(route('proveedores.edit',$provider->id));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData['fields']);
        $this->assertNotEmpty($responseData['item']);
        $expectedFields = ['_token', 'name', 'contact_name', 'phone', 'address', 'email'];
        $fieldsInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push( $fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }
    

}
