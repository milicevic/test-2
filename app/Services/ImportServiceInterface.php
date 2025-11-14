<?php

namespace App\Services;

use App\Models\User;
interface ImportServiceInterface
{
    public function getSearchAndPaginated(string $importType, string $fileKey, ?string $search, int $perPage = 10);

    public function uploadAndDispatch(string $importType, array $files, User $user): void;

    public function getLogs(string $model, int $rowNumber);

    public function delete(string $model, int $id, User $user): void;

    public function getHeaders(string $importType, string $fileKey): array;

    public function getModelClass(string $importType, string $fileKey): string;
    public function getSetupByPermission($user): array;
}
