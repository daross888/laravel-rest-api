<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Support\Collection;

class TodoRepository
{

    public function all(): Collection
    {
        return Todo::query()->orderBy('id', 'desc')->get();
    }

    public function findById(int $id): ?Todo
    {
        return Todo::find($id);
    }

    public function create(array $data): Todo
    {
        return Todo::create([
            'title' => $data['title'],
            'done' => false,
        ]);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
        return $todo;
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }
}
