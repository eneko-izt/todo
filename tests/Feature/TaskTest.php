<?php

namespace Tests\Unit;

use App\Tag;
use App\Role;
use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Test Task CRUD: Run the test with vendor/bin/phpunit in attached shell
     */

    public function test_api_alltasks_route_requires_authentication()
    {
        $response = $this->getJson(route('api.alltasks'));
        $response->assertStatus(401); // Unauthorized
    }

    public function test_api_alltasks_route_requires_permission()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->getJson(route('api.alltasks'));
        $response->assertStatus(403); // Forbidden if user cannot viewAllTasks
    }

    public function test_api_alltasks_route_returns_success_for_authorized_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->mock(TaskPolicy::class, function ($mock) {
            $mock->shouldReceive('viewAllTasks')->andReturn(true);
        });

        $response = $this->getJson(route('api.alltasks'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    }

    public function test_it_returns_tasks_for_datatable_with_correct_format()
    {
        $role = factory(Role::class)->create(['name' => 'admin']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role);

        $this->actingAs($user);

        // Create related models
        $column = factory(Column::class)->create();
        $tag = factory(Tag::class)->create();
        $sharingUser = factory(User::class)->create();

        // Create tasks
        $task = factory(Task::class)->create([
            'text' => 'Test Task',
            'user_id' => $user->id,
            'column_id' => $column->id,
            'active' => 1,
        ]);

        $task->tags()->attach($tag->id);
        $task->sharingUsers()->attach($sharingUser->id);

        // Make API request simulating DataTables parameters
        $response = $this->getJson(route('api.alltasks', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => 'Test'],
            'order' => [
                ['column' => 1, 'dir' => 'asc'] // order by text
            ],
        ]));

        // Assert JSON structure
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'draw',
                     'recordsTotal',
                     'recordsFiltered',
                     'data' => [
                         '*' => ['id', 'text', 'owner', 'column', 'tags', 'sharingUsers']
                     ],
                 ]);

        // Assert data contains the task
        $responseData = $response->json('data')[0];
        $this->assertEquals('Test Task', $responseData['text']);
        $this->assertEquals($user->name, $responseData['owner']);
        $this->assertEquals($column->name, $responseData['column']);
        $this->assertStringContainsString($tag->name, $responseData['tags']);
        $this->assertStringContainsString($sharingUser->name, $responseData['sharingUsers']);

        // Assert records count
        $this->assertEquals(Task::active()->count(), $response->json('recordsTotal'));
    }

    /** @test */
    public function test_it_filters_tasks_based_on_search()
    {
        $column = factory(Column::class)->create();
        $role = factory(Role::class)->create(['name' => 'admin']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role);
        $this->actingAs($user);

        $task1 = factory(Task::class)->create(['text' => 'Hello World', 'user_id' => $user->id, 'active' => 1, 'column_id' => $column->id]);
        $task2 = factory(Task::class)->create(['text' => 'Another Task', 'user_id' => $user->id, 'active' => 1, 'column_id' => $column->id]);

        $response = $this->getJson(route('api.alltasks', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => 'Hello'],
        ]));

        $responseData = $response->json('data');
        $this->assertCount(1, $responseData);
        $this->assertEquals('Hello World', $responseData[0]['text']);
    }

}
