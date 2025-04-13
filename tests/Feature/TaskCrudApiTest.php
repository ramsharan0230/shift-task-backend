<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudApiTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $header;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $token = auth('api')->login($this->user);

        $this->headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ];
    }

    public function test_can_fetch_all_tasks()
    {
        Task::factory()->count(3)->create(['created_by' => $this->user->id]);

        $response = $this->getJson('/api/tasks', $this->headers);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_can_create_new_task()
    {
        $payload = [
            'title' => 'CRUD for Task',
            'description' => 'Description for Task',
            'status' => 'TODO' // either TODO, or INPROGRESS, or QA, or DONE
        ];
        $response = $this->postJson('/api/tasks', $payload, $this->headers);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'CRUD for Task']);
    }

    public function test_can_show_task()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id]);
        $response = $this->getJson("/api/tasks/{$task->id}", $this->headers);
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $task->id]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id]);

        $payload = ['title' => 'Updated Title'];

        $response = $this->patchJson("/api/tasks/{$task->id}", $payload, $this->headers);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create(['created_by'=> $this->user->id]);
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $this->headers);
        $response->assertStatus(200);
        $this->assertDatabaseMissing("tasks", ["id"=> $task->id]);
    }
}
