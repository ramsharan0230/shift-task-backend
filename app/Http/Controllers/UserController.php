<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ApiResponse;
    
    public function index(): JsonResponse
    {
        $data = [
            'users' => UserResource::collection(User::all())
        ];
        return $this->success($data, "Created successfully", Response::HTTP_CREATED);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            
            ]);
            
            $userCreated = $user->fresh();
            $data = [
                'user' => new UserResource($userCreated),
                'token' => JWTAuth::fromUser($userCreated),
            ];
            return $this->success($data, "Created successfully", Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());   
            return $this->error($exception->getMessage(), "Internal Server Error", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
