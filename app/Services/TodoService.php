<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Support\Collection;

class TodoService
{

    public function __construct(
        protected TodoRepository $todoRepository
    ) {}

    public function findAll(?int $user_id = null): Collection
    {
        return $this->todoRepository->all($user_id);
    }

    /**
     * @throws NotFoundException
     */
    public function findById(int $id, ?int $user_id = null): ?Todo
    {
        if (!$todo = $this->todoRepository->findById($id, $user_id)) {
            throw new NotFoundException('Todo not found');
        }

        return $todo;
    }

    public function create(array $data): Todo
    {
        return $this->todoRepository->create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        return $this->todoRepository->update($todo, $data);
    }

    public function markAsDone(Todo $todo): Todo
    {
        return $this->todoRepository->update($todo, [
            'done' => true,
        ]);
    }

    public function delete(Todo $todo): bool
    {
        return $this->todoRepository->delete($todo);
    }
}
