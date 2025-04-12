<?php 

namespace Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Task;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class TaskRepository implements TaskEloquentInterface {

    public function fetchAllTasks(): Collection
    {
        try {
            return Task::with('creator')->get();
        } catch (QueryException $e) {
            Log::error('Database error fetching tasks: ' . $e->getMessage());
            throw new Exception('Failed to fetch tasks due to a database error.');
        } catch (Exception $e) {
            Log::error('Unexpected error fetching tasks: ' . $e->getMessage());
            throw new Exception('An unexpected error occurred.');
        }
    }

    public function store(array $data): Task
    {
        try{
            $user = auth('api')->user();
            $data['created_by'] = $user->id;
            $task = Task::create($data);
            return $task->load('creator');
            
        }catch (Exception $e) {
            Log::error('Error while storing task: '.$e->getMessage());
            throw new Exception('Something went wrong while creating new task. Please try again later.');
        }
    }

    public function show(int $id): Task
    {
        try{
            return Task::with('creator')->findOrFail($id);
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
            $task = Task::findOrFail($id);
            $task->delete();
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