<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Project;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Database\Factories\UserFactory; 
use Inertia\Testing\AssertableInertia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProjectControllerTest extends TestCase
{    

    use WithoutMiddleware;

    /** @test */
    public function it_can_list_projects()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get(route('proyectos.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Project/Projects')
                 ->has('projects');
        });
    }

    /** @test */
    public function it_can_create_a_project()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $data = [
            'name' => 'Test Project',
            'address' => 'Test Address',
            'comission' => 1000,
            'client_id' => 1,
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('proyectos.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }

    /** @test */
    public function it_can_show_a_project()
    {
        
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get(route('proyectos.show',1));

        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Project/ProjectDetail')
                 ->has('project');
        });

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        
        $user = User::find(1);
        $this->actingAs($user);
        $project = Project::where('name','Test Project')->first();
        $response = $this->delete(route('proyectos.destroy',$project->id));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
    
    public function test_it_can_show_create_form_project()
    {
        
        $response = $this->get(route('proyectos.create'));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData);
        $expectedFields = ['_token', 'name', 'address', 'comission', 'client_id'];
        $fielsdInResponse = [];
        foreach ($responseData as $field) {
            array_push( $fielsdInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields,$fielsdInResponse);
        $this->assertEmpty($diff);
    }

    public function test_it_can_show_edit_form_project()
    {
        $project = Project::find(1);
        $response = $this->get(route('proyectos.edit',$project->id));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertNotEmpty($responseData['fields']);
        $this->assertNotEmpty($responseData['item']);
        $expectedFields = ['_token', 'name', 'address', 'comission', 'client_id'];
        $fielsdInResponse = [];
        foreach ($responseData['fields'] as $field) {
            array_push( $fielsdInResponse, $field['slug']);
        }
        $diff = array_diff($expectedFields,$fielsdInResponse);
        $this->assertEmpty($diff);
    }
    

}