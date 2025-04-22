<?php
// app/Repositories/TaskRepository.php

namespace Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class TaskRepository implements TaskEloquentInterface
{
    public function fetchAllTasks(): Collection
    {
        try {
            return Task::with('creator')->get();
        } catch (QueryException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to fetch tasks.',
                $e
            );
        }
    }

    public function store(array $data): Task
    {
        try {
            $data['created_by'] = auth('api')->id();
            $task = Task::create($data);
            return $task->load('creator');
        } catch (QueryException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to create task.',
                $e
            );
        }
    }

    public function show(int $id): Task
    {
        try {
            return Task::with('creator')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException("Task not found with id {$id}.", $e);
        } catch (QueryException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to fetch task.',
                $e
            );
        }
    }

    public function update(int $id, array $data): Task
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($data);
            return $task;
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException("Task not found with id {$id}.", $e);
        } catch (QueryException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to update task.',
                $e
            );
        }
    }

    public function destroy(int $id): void
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException("Task not found with id {$id}.", $e);
        } catch (QueryException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to delete task.',
                $e
            );
        }
    }
}
