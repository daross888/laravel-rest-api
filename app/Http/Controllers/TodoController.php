<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TodoController extends BaseApiController
{
    public function __construct(
        protected TodoService $todoService,
    ) {}

    public function index(): JsonResponse
    {
        $todos = $this->todoService->findAll(Auth::id());

        return $this->successResponse($todos);
    }

    public function show(string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->findById($id, Auth::id());
        } catch (NotFoundException $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse($todo);
    }

    public function create(CreateTodoRequest $request): JsonResponse
    {
        try {
            $todo = $this->todoService->create([
                'user_id' => Auth::id(),
                'title' => $request->validated('title'),
            ]);
        } catch (\Exception) {
            return $this->failedResponse('Could not create Todo.');
        }

        return $this->successResponse($todo, 201);
    }

    public function update(string $id, UpdateTodoRequest $request): JsonResponse
    {
        try {
            $todo = $this->todoService->findById($id, Auth::id());
            $updatedTodo = $this->todoService->update($todo, $request->validated());
        } catch (NotFoundException $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        } catch (\Exception) {
            return $this->failedResponse('Could not update Todo.');
        }

        return $this->successResponse($updatedTodo);
    }

    public function done(string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->findById($id, Auth::id());
            $updatedTodo = $this->todoService->markAsDone($todo);
        } catch (NotFoundException $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->failedResponse('Could not mark Todo as Done.');
        }

        return $this->successResponse($updatedTodo);
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->findById($id, Auth::id());
            $this->todoService->delete($todo);
        } catch (NotFoundException $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->failedResponse('Could not delete Todo.');
        }

        return $this->successResponse(null);
    }
}
