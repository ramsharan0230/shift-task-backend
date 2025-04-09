<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Repositories\LoginEloquentInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiResponse;
    private LoginEloquentInterface $loginEloquentRepository;
    
    public function __construct(LoginEloquentInterface $loginRepository)
    {
        $this->loginEloquentRepository = $loginRepository;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->loginEloquentRepository->login($request->email, $request->password);
        return $this->success($user, "Login successful");
    } 
    
    public function refreshToken(): JsonResponse
    {
        try {
            $data = $this->loginEloquentRepository->refresh();
            return $this->success($data, "Token refreshed successfully");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Refresh failed", Response::HTTP_UNAUTHORIZED);
        }
    }
}
