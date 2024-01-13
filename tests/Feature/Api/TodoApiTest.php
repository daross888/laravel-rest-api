<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;
    private $todoId = 3;

    public function setUp(): void
    {
        parent::setUp();

        Todo::factory(10)->create();
    }

    public function test_can_retrieve_all_todos(): void
    {
        $response = $this->get('/api/v1/todo');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertTrue($responseData['success']);
        $this->assertCount(10, $responseData['data']);
    }

    public function test_can_find_one_todo(): void
    {
        $todo = Todo::find($this->todoId);

        $response = $this->get('/api/v1/todo/' . $this->todoId);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertTrue($responseData['success']);
        $this->assertEquals($todo->title, $responseData['data']['title']);
    }

    public function test_can_create_todo(): void
    {
        $data = [
            'title' => 'New Todo',
        ];

        $response = $this->post('/api/v1/todo', $data);
        $response->assertStatus(201);

        $responseData = $response->json();

        $this->assertEquals($data['title'], $responseData['data']['title']);
        $this->assertFalse($responseData['data']['done']);
    }

    public function test_can_update_todo(): void
    {
        $data = [
            'title' => 'Updated Todo',
        ];

        $response = $this->put('/api/v1/todo/' . $this->todoId, $data);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals($data['title'], $responseData['data']['title']);
    }

    public function test_can_mark_todo_done(): void
    {
        $todo = Todo::find($this->todoId);

        $this->assertFalse($todo->done? true : false);

        $response = $this->put('/api/v1/todo/' . $this->todoId . '/done');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals($this->todoId, $responseData['data']['id']);
        $this->assertTrue($responseData['data']['done']);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::find($this->todoId);
        $this->assertNotNull($todo);

        $response = $this->delete('/api/v1/todo/' . $this->todoId);
        $response->assertStatus(200);

        $this->assertNull(Todo::find($this->todoId));
        $this->assertCount(9, Todo::all());
    }
}
