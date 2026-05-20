<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function create(array $data): Model;

    public function update(Model $model, array $data): bool;

    public function delete(Model $model): bool;
}
