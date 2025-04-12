<?php

namespace Repositories;

use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginRepository implements LoginEloquentInterface
{
    public function login(string $email, string $password): array
    {
        $credentials = ['email' => $email, 'password' => $password];
        Log::info(json_encode($credentials));

        try {
            if(!$token = auth('api')->attempt($credentials)) {
                throw new UnauthorizedHttpException('', 'Invalid credentials');
            }
            Log::info("expiry: ".config('jwt.ttl') * 60 );

            return [
                'user' => new UserResource(Auth::user()),
                'token' => $token,
                'expires_in' => (int) config('jwt.ttl') * 60,
            ];
        } catch (Exception $exception) {
            Log::error('Login error: ' . $exception->getMessage());
            throw new UnauthorizedHttpException($exception->getMessage(), 'Login failed');
        }
    }

    public function logout(): void
    {
        try {
            Auth::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            Log::info("Logout successful");
        } catch (Exception $exception) {
            Log::error('Logout error: ' . $exception->getMessage());
            throw new Exception('Logout failed: ' . $exception->getMessage());
        }
    }

    public function refresh(): array
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            $user = Auth::user();

            return [
                'user' => $user,
                'token' => $newToken,
                'expires_in' => (int) config('jwt.ttl') * 60,
            ];
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            Log::error('Token refresh error: ' . $e->getMessage());
            throw new UnauthorizedHttpException('', 'Token is invalid or expired');
        } catch (Exception $e) {
            Log::error('Unexpected error while refreshing token: ' . $e->getMessage());
            throw new UnauthorizedHttpException('', 'Token refresh failed');
        }
    }
}
