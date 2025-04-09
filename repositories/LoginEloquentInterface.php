<?php

namespace Repositories;

interface LoginEloquentInterface{
    public function login(string $email, string $password);
    public function logout();
    public function refresh();
}