<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Repositories\LoginEloquentInterface;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    use ApiResponse;
    private LoginEloquentInterface $loginEloquentRepository;
    
    public function __construct(LoginEloquentInterface $loginRepository)
    {
        $this->loginEloquentRepository = $loginRepository;
    }

    public function makeLogin(LoginRequest $request): JsonResponse
    {
        try{
            $userDetails = $this->loginEloquentRepository->login($request->email, $request->password);
            
            return $this->success($userDetails, "Login successful");
        }catch(Exception $e){
            Log::info("error: ".$e->getMessage());
            return $this->error($e->getMessage(), "Unsuccessful", Response::HTTP_UNAUTHORIZED);
        }
    } 

    public function logout(): JsonResponse
    {
        try{
            $this->loginEloquentRepository->logout();
            
            return $this->success([], "Logout successful");
        }catch(Exception $e){
            Log::info("error: ".$e->getMessage());
            return $this->error($e->getMessage(), "Unsuccessful", Response::HTTP_UNAUTHORIZED);
        }
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
