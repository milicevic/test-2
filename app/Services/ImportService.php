<?php
namespace App\Services;

use App\Jobs\ProcessImportJob;
use App\Models\User;
use App\Repositories\ImportRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ImportService implements ImportServiceInterface
{
    protected ImportRepositoryInterface $repository;

    public function __construct(ImportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getSearchAndPaginated(string $importType, string $fileKey, ?string $search, int $perPage = 10)
    {
        $modelClass = $this->getModelClass($importType, $fileKey);
        $fields = $this->getHeaders($importType, $fileKey);

        return $this->repository->paginate($modelClass, $fields, $search, $perPage);
    }
      public function getSearchQuery(string $importType, string $fileKey, ?string $search, int $perPage = 10)
    {
        $modelClass = $this->getModelClass($importType, $fileKey);
        $fields = $this->getHeaders($importType, $fileKey);

        return $this->repository->getQuery($modelClass, $fields, $search, $perPage);
    }


    public function uploadAndDispatch(string $importType, array $files, User $user): void
    {
        $paths = [];
        foreach ($files as $key => $file) {
            $originalName = $file->getClientOriginalName();
            $paths[$key] = $file->storeAs('imports', $originalName);
        }

        ProcessImportJob::dispatchSync($importType, $paths, $user);
    }

    public function getLogs(string $model, int $rowNumber)
    {
        return $this->repository->getLogs($model, $rowNumber);
    }

    public function delete(string $model, int $id, User $user): void
    {
        $this->repository->delete($model, $id, $user);
    }

    public function getHeaders(string $importType, string $fileKey): array
    {
        return Config::get("import_data.$importType.files.$fileKey.headers_to_db", []);
    }

    public function getModelClass(string $importType, string $fileKey): string
    {
        return "App\\Models\\" . Str::studly($importType) . Str::studly($fileKey);
    }
    public function getSetupByPermission($user): array
    {
        $setup = Config::get('import_data');
        $userPermissions = $user->permissions->pluck('name')->toArray();
        $allowedPermissions = array_flip($userPermissions);

        $setupConfig = [];
        foreach ($setup as $type => $typeConfig) {
            $requiredPermission = $typeConfig['permission_required'] ?? null;
            if ($requiredPermission === null || isset($allowedPermissions[$requiredPermission])) {
                $setupConfig[$type] = $typeConfig;
            }
        }

        return $setupConfig;
    }

}
