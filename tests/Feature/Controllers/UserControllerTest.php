<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Database\Factories\UserFactory; 
use Inertia\Testing\AssertableInertia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserControllerTest extends TestCase
{    

    use WithoutMiddleware;

    /** @test */
    public function it_can_list_users()
    {
        // Crear y autenticar un usuario con el rol "admin"
        $user = UserFactory::new()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        // Realizar la solicitud a la ruta 'usuarios.index'
        $response = $this->get(route('usuarios.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('User/Users')
                 ->has('users');
        });
    }

    /** @test */
    public function it_can_create_a_user()
    {
        
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');
        $this->actingAs($adminUser);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 1,
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('usuarios.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        
        $user = User::factory()->create();

        $response = $this->get(route('usuarios.show', $user->id));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('User/UserDetail')
                 ->has('user');
        });

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['name' => $user->name]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');
        $user = User::factory()->create();

        $response = $this->actingAs($adminUser)->delete(route('usuarios.destroy', $user->id));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
    
    public function test_it_can_show_create_form_user()
    {
        
        $response = $this->get(route('usuarios.create'));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData);
        $expectedFields = ['_token', 'name', 'email', 'role'];
        $fieldsInResponse = [];
        foreach ($responseData as $field) {
            array_push( $fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }

    public function test_it_can_show_edit_form_user()
    {
        $user = User::factory()->create();

        $response = $this->get(route('usuarios.edit', $user->id));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData['fields']);
        $this->assertNotEmpty($responseData['item']);
        $expectedFields = ['_token', 'name', 'email', 'role'];
        $fieldsInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push( $fieldsInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields, $fieldsInResponse);
        $this->assertEmpty($diff);
    }
    

}
