<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{

    public function test_api_index(){

        $response = $this->getJson("api/v1/tasks");

        $response->assertStatus(200);
    }

    public function test_api_store(){
        
        $data = [
            "title" => "Test",
            "description" => "nullable",
            "user_id" => 1,
        ];

        $response = $this->postJson("api/v1/tasks", $data);

        $response->assertStatus(201);
    }
}
