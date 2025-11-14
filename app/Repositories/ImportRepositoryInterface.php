<?php

namespace App\Repositories;

interface ImportRepositoryInterface
{
    public function paginate(string $modelClass, array $fields, ?string $search, int $perPage);

    public function getQuery(string $modelClass, array $fields, ?string $search);

    public function delete(string $model, int $id, $user);

    public function getLogs(string $model, int $rowNumber);
}
