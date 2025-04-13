<?php 

namespace Repositories;
use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskEloquentInterface {
    public function fetchAllTasks(): Collection;
    public function store(array $data): Task;
    public function show(int $id): Task;
    public function update(int $id, array $data);
    public function destroy(int $id): void;
}