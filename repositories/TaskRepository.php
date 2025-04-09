<?php 

namespace Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Task;
use Exception;

class TaskRepository implements TaskEloquentInterface{
    public function fetchAllTasks(): Collection
    {
        try{
            return Task::all();
        }catch (Exception $e) {
            Log::error('Error while fetching tasks: '.$e->getMessage());
            throw new Exception('Something went wrong while fetching tasks. Please try again later.');
        }
    }
    public function store(array $data): Task
    {
        try{
            $task = Task::create($data);
            $taskCreated = $task->fresh();
            return $taskCreated;
        }catch (Exception $e) {
            Log::error('Error while storing task: '.$e->getMessage());
            throw new Exception('Something went wrong while creating new task. Please try again later.');
        }
    }
    public function show(int $id): Task
    {
        try{
            $task = Task::findOrFail($id);
            return $task;
        }catch (ModelNotFoundException $e) {
            Log::error("Task not found with given id: {$id} : ".$e->getMessage());
            throw new Exception("Task not found with given id: {$id}. Please try with valid id.");
        }
        catch (Exception $e) {
            Log::error('Error while fetching task: '.$e->getMessage());
            throw new Exception('Something went wrong while fetching task. Please try again later.');
        }
    }
    public function update(int $id, array $data): Task
    {
        try{
            $task = Task::findOrFail($id);
            $task->update($data);
            return $task;
        }catch (ModelNotFoundException $e) {
            Log::error("Task not found with given id: {$id} : ".$e->getMessage());
            throw new Exception("Task not found with given id: {$id}. Please try with valid id.");
        }
        catch (Exception $e) {
            Log::error('Error while updating task: '.$e->getMessage());
            throw new Exception('Something went wrong while updating task. Please try again later.');
        }
    }
    public function destroy(int $id): void
    {
        try{
            $task = Task::findOrFail($id)->delete();
        }catch (ModelNotFoundException $e) {
            Log::error("Task not found with given id: {$id} : ".$e->getMessage());
            throw new Exception("Task not found with given id: {$id}. Please try with valid id.");
        }
        catch (Exception $e) {
            Log::error('Error while deleting task: '.$e->getMessage());
            throw new Exception('Something went wrong while deleting task. Please try again later.');
        }
    }
}