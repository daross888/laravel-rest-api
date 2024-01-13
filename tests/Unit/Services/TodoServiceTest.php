<?php

namespace Tests\Unit\Services;

use App\Models\Todo;
use App\Repositories\TodoRepository;
use App\Services\TodoService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TodoServiceTest extends TestCase
{
    private $mockRepository;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = $this->createMock(TodoRepository::class);
        $this->service = new TodoService($this->mockRepository);
    }

    public function test_can_find_all_todos(): void
    {
        $this->mockRepository->expects($this->once())
            ->method('all')
            ->willReturn(collect([
                new Todo(['title' => 'Sample Todo', 'done' => false]),
                new Todo(['title' => 'Sample Todo 2', 'done' => false]),
            ]));

        $todos = $this->service->findAll();

        $this->assertCount(2, $todos);
        $this->assertInstanceOf(Collection::class, $todos);
        $this->assertInstanceOf(Todo::class, $todos->first());
    }

    public function test_can_find_todo_by_id(): void
    {
        $todoId = 1;
        $todoData = [
            'title' => 'Sample Todo',
            'done' => false,
        ];
        $todo = new Todo($todoData);
        $todo->id = $todoId;

        $this->mockRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($todoId))
            ->willReturn($todo);

        $todoResult = $this->service->findById($todoId);

        $this->assertInstanceOf(Todo::class, $todoResult);
        $this->assertEquals($todoId, $todoResult->id);
        $this->assertEquals('Sample Todo', $todoResult->title);
    }

    public function test_can_create_todo(): void
    {
        $todoData = [
            'title' => 'Sample Todo',
            'done' => false,
        ];

        $this->mockRepository->expects($this->once())
            ->method('create')
            ->with($this->equalTo($todoData))
            ->willReturn(new Todo($todoData));

        $result = $this->service->create($todoData);

        $this->assertInstanceOf(Todo::class, $result);
        $this->assertEquals('Sample Todo', $result->title);
        $this->assertFalse($result->done);
    }

    public function test_can_update_todo(): void
    {
        $todoId = 1;
        $originalData = [
            'title' => 'Original Todo'
        ];
        $updatedData = [
            'title' => 'Updated Todo'
        ];

        $todo = new Todo($originalData);
        $todo->id = $todoId;

        $this->mockRepository->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($todo),
                $this->equalTo($updatedData),
            )
            ->willReturnCallback(function ($id, $data) use ($todo) {
                $todo->fill($data);
                return $todo;
            });

        $updatedTodo = $this->service->update($todo, $updatedData);

        $this->assertInstanceOf(Todo::class, $updatedTodo);
        $this->assertEquals($todoId, $updatedTodo->id);
        $this->assertEquals('Updated Todo', $updatedTodo->title);
    }

    public function test_can_mark_todo_done(): void
    {
        $todoData = [
            'title' => 'Sample Todo',
            'done' => false,
        ];
        $todo = new Todo($todoData);

        $this->mockRepository->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($todo),
                $this->equalTo([
                    'done' => true
                ]),
            )
            ->willReturnCallback(function ($id, $data) use ($todo) {
                $todo->fill($data);
                return $todo;
            });

        $doneTodo = $this->service->markAsDone($todo);

        $this->assertInstanceOf(Todo::class, $doneTodo);
        $this->assertTrue($doneTodo->done);
    }

    public function test_can_delete_todo(): void
    {
        $todoId = 1;
        $todoData = [
            'title' => 'Sample Todo',
            'done' => false,
        ];
        $todo = new Todo($todoData);
        $todo->id = $todoId;

        $this->mockRepository->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($todo))
            ->willReturn(true);

        $result = $this->service->delete($todo);

        $this->assertTrue($result);
    }
}
