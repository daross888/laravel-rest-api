<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;
    private $todoId = 3;

    public function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create();
        Todo::factory(5)->create([
            'user_id' => 1
        ]);

        Todo::factory(5)->create([
            'user_id' => 2
        ]);
    }

    public function test_cannot_retrieve_todos_unauthenticated(): void
    {
        $this->assertGuest();
        $response = $this->get('/api/v1/todo', [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_can_retrieve_all_todos(): void
    {
        Sanctum::actingAs(User::first());
        $response = $this->get('/api/v1/todo', [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $responseData = $response->json();

        $this->assertTrue($responseData['success']);
        $this->assertCount(5, $responseData['data']);
    }

    public function test_can_find_one_todo(): void
    {
        Sanctum::actingAs(User::first());
        $todo = Todo::find($this->todoId);

        $response = $this->get('/api/v1/todo/' . $this->todoId, [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $responseData = $response->json();

        $this->assertTrue($responseData['success']);
        $this->assertEquals($todo->title, $responseData['data']['title']);
    }

    public function test_can_create_todo(): void
    {
        Sanctum::actingAs(User::first());
        $data = [
            'title' => 'New Todo',
        ];

        $response = $this->post('/api/v1/todo', $data, [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $responseData = $response->json();

        $this->assertEquals($data['title'], $responseData['data']['title']);
        $this->assertFalse($responseData['data']['done']);
    }

    public function test_can_update_todo(): void
    {
        Sanctum::actingAs(User::first());
        $data = [
            'title' => 'Updated Todo',
        ];

        $response = $this->put('/api/v1/todo/' . $this->todoId, $data, [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $responseData = $response->json();

        $this->assertEquals($data['title'], $responseData['data']['title']);
    }

    public function test_can_mark_todo_done(): void
    {
        Sanctum::actingAs(User::first());
        $todo = Todo::find($this->todoId);

        $this->assertFalse($todo->done? true : false);

        $response = $this->put('/api/v1/todo/' . $this->todoId . '/done', [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $responseData = $response->json();

        $this->assertEquals($this->todoId, $responseData['data']['id']);
        $this->assertTrue($responseData['data']['done']);
    }

    public function test_can_delete_todo(): void
    {
        Sanctum::actingAs(User::first());
        $todo = Todo::find($this->todoId);
        $this->assertNotNull($todo);

        $response = $this->delete('/api/v1/todo/' . $this->todoId, [], [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertNull(Todo::find($this->todoId));
        $this->assertCount(9, Todo::all());
    }
}
