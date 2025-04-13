<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get("/", function(){
    return "Welcome to board api development.";
});

Route::prefix("/auth")->group(function(){
    Route::post("/register", [UserController::class,"store"])->name("auth.register");
    Route::post("/login", [AuthController::class,"makeLogin"])->name("auth.login");
    Route::middleware(["auth:api"])->group(function(){
        Route::post("/logout", [AuthController::class,"logout"])->name("auth.logout");
        Route::post("/refresh", [AuthController::class,"refreshToken"])->name("auth.login.refresh");
    });
});

Route::middleware(["auth:api"])->group(function () {
    Route::get('/users/{id}', [UserController::class, "show"])->name('users.show');
    Route::get("/users", [UserController::class,"index"])->name("users.index");

    Route::prefix('/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/{id}', [TaskController::class, 'show'])->name('tasks.show');
        Route::patch('/{id}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
});
