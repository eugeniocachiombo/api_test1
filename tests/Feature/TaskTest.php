<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{

    public $id = 2;
    public $user_id = 1;

    public function test_api_index()
    {

        $response = $this->getJson("api/v1/tasks");

        $response->assertStatus(200);
    }

    public function test_api_store()
    {

        $data = [
            "title" => "Test",
            "description" => "nullable",
            "user_id" => $this->user_id,
        ];

        $response = $this->postJson("api/v1/tasks", $data);

        $response->assertStatus(201);
    }

    public function test_api_show()
    {
        $task = Task::find($this->id);
        $response = $this->getJson("api/v1/tasks/{$task->id}");
        $response->assertStatus(200);
    }

    public function test_api_update()
    {
        $task = Task::find($this->id);
        $data = [
            "status" => "em_andamento",
            "user_id" => $this->user_id,
        ];
        $response = $this->putJson("api/v1/tasks/{$task->id}", $data);
        $response->assertStatus(200);
    }

    public function test_api_delete()
    {
        $task = Task::find($this->id);
        $response = $this->deleteJson("api/v1/tasks/{$task->id}");
        $response->assertStatus(200);
    }
}
