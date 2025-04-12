<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post("/auth/register", [UserController::class,"store"])->name("auth.register");
Route::post("/auth/login", [AuthController::class,"makeLogin"])->name("auth.login");

Route::middleware(["auth:api"])->group(function () {
    Route::post("/auth/logout", [AuthController::class,"logout"])->name("auth.logout");
    Route::post("/auth/refresh", [AuthController::class,"refreshToken"])->name("auth.login.refresh");
    Route::get('/users/{id}', [UserController::class, "show"])->name('users.show');
    Route::get("/users", [UserController::class,"index"])->name("users.index");
});



Route::prefix('/tasks')->middleware('auth:api')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});