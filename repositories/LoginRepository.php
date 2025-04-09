<?php

namespace Repositories;

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

        try {
            if (!$token = Auth::attempt($credentials)) {
                throw new UnauthorizedHttpException('', 'Invalid credentials');
            }

            return [
                'user' => Auth::user(),
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60,
            ];
        } catch (Exception $exception) {
            Log::error('Login error: ' . $exception->getMessage());
            throw new UnauthorizedHttpException('', 'Login failed');
        }
    }

    public function logout(): void
    {
        try {
            Auth::logout();
        } catch (Exception $exception) {
            Log::error('Logout error: ' . $exception->getMessage());
            throw new Exception('Something went wrong while login out: '.$exception->getMessage(), $exception->getCode());
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
                'expires_in' => auth()->factory()->getTTL() * 60,
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
