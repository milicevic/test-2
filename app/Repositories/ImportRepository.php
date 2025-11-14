<?php
namespace App\Repositories;

use App\Models\AuditLog;

class ImportRepository implements ImportRepositoryInterface
{
    public function paginate(string $modelClass, array $fields, ?string $search, int $perPage)
    {
        $query = $modelClass::query();

        if ($search) {
            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field => $config) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage)->appends(['search' => $search]);
    }

    public function getQuery(string $modelClass, array $fields, ?string $search)
    {
        $query = $modelClass::query();

        if ($search) {
            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field => $config) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        return $query;
    }
    public function getLogs(string $model, int $rowNumber){
        return AuditLog::where('model', $model)
            ->where('row_number', $rowNumber)
            ->get();
    }

    public function delete(string $model, int $id, $user)
    {
        $record = $model::findOrFail($id);
        $record->delete();
    }
}
