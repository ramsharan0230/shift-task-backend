<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;


Route::get("/users", [UserController::class,"index"])->name("users.index");
Route::post("/auth/register", [UserController::class,"store"])->name("auth.register");
Route::post("/auth/login", [AuthController::class,"makeLogin"])->name("auth.login");
Route::post("/auth/refresh", [AuthController::class,"refreshToken"])->name("auth.login.refresh");
Route::post("/auth/logout", [AuthController::class,"logout"])->name("auth.logout");
Route::get('/users/{id}', [UserController::class, "show"])->name('users.show');

// Route::get("/tasks")

