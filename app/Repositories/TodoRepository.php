<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Support\Collection;

class TodoRepository
{

    public function all(?int $user_id = null): Collection
    {
        return Todo::query()->when($user_id, function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->orderBy('id', 'desc')->get();
    }

    public function findById(int $id, ?int $user_id = null): ?Todo
    {
        return Todo::where('id', $id)->when($user_id, function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->first();
    }

    public function create(array $data): Todo
    {
        return Todo::create([
            'user_id' => $data['user_id'],
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
