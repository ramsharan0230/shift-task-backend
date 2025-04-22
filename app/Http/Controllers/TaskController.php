<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Repositories\TaskEloquentInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiResponse;
    private TaskEloquentInterface $taskEloquentRepository;
    
    public function __construct(TaskEloquentInterface $taskRepository)
    {
        $this->taskEloquentRepository = $taskRepository;
    }

    public function index(): JsonResponse
    {
        try{
            $tasks = $this->taskEloquentRepository->fetchAllTasks();
            $taskResource = TaskResource::collection($tasks);
            return $this->success($taskResource, "Tasks fetched successfully.");
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), "Unsuccessfull", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    } 
    
    public function store(TaskStoreRequest $request): JsonResponse
    {
        try{
            $data = $request->all();
            $task = $this->taskEloquentRepository->store($data);
            $taskResource = new TaskResource($task);
            return $this->success($taskResource, "Tasks created successfully.");
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), "Unsuccessfull", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    } 

    public function show(int $id): JsonResponse
    {
        try{
            $task = $this->taskEloquentRepository->show($id);
            $taskResource = new TaskResource($task);
            return $this->success($taskResource, "Task has shown successfully.");
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), "Unsuccessfull", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } 

    public function update(int $id, TaskStoreRequest $request): JsonResponse
    {
        try{
            $data = $request->all();
            $task = $this->taskEloquentRepository->update($id, $data);
            return $this->success($task, "Task updated successfully.");
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), "Unsuccessfull", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try{
            $this->taskEloquentRepository->destroy($id);
            return $this->success([], "Task deleted successfully.");
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), "Unsuccessfull", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } 
}
