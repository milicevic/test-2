<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\ImportAudit;
use App\Models\ImportLog;
use App\Models\User;
use App\Models\ValidationLogs;
use App\Notifications\ImportCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $importType;
    protected array $files; // Array of uploaded file paths
    protected array $config;
    protected User $user;

    /**
     * @param string $importType - e.g. 'orders'
     * @param array $files - Array of uploaded file paths
     */
    public function __construct(string $importType, array $files, User $user)
    {

        $this->importType = $importType;
        $this->files = $files;
        $this->config = config("import_data.$importType");
        $this->user = $user;
    }

    public function handle()
    {
        $importConfig = $this->config;

        foreach ($this->files as $fileKey => $filePath) {

            if (!isset($importConfig['files'][$fileKey])) {
                Log::warning("Unknown file key '$fileKey' for import type '{$this->importType}'");
                continue;
            }

            $fileConfig = $importConfig['files'][$fileKey];
            $headers = $fileConfig['headers_to_db'] ?? [];
            $updateKeys = $fileConfig['update_or_create'] ?? [];
            $modelClass = Str::studly($this->importType) . Str::studly($fileKey);


            if (!class_exists("App\\Models\\{$modelClass}")) {
                Log::error("Missing or invalid model for file '$fileKey' in import type '{$this->importType}'");
                continue;
            }

            $fullPath = storage_path("app/private/{$filePath}");
            if (!file_exists($fullPath)) {
                Log::error("File not found: $fullPath");
                continue;
            }
            $rows = $this->readFile($fullPath, $headers);
            $errors = [];
            foreach ($rows as $rowIndex => $row) {
                $data = [];
                $rules = [];
                $importLog = ImportLog::create([
                    'user_id' => $this->user->id,
                    'import_type' => $this->importType,
                    'file_name' => $fileKey,
                    'original_file' => $filePath,
                    'status' => ImportLog::STATUS_SUCCESSFUL,
                ]);
                foreach ($headers as $headerName => $columnConfig) {
                    $dbColumn = $headerName;
                    $data[$dbColumn] = $row[$headerName] ?? null;
                    $rules[$dbColumn] = $this->flattenValidationRules($columnConfig['validation'] ?? []);
                }
                Log::info("Validator:", $data, $rules);

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    foreach ($validator->errors()->toArray() as $col => $messages) {
                        ValidationLogs::create([

                            'table_name' => $modelClass::getTable(),
                            'row_number' => $rowIndex + 1,
                            'column_name' => $col,
                            'new_value' => $data[$col],
                            'message' => implode(', ', $messages),
                            'import_log_id' => $importLog->id
                        ]);
                        Log::info("Error on validator:", $messages);
                    }
                    continue;
                }

                try {
                    $query = "App\\Models\\{$modelClass}"::query();

                    foreach ($updateKeys as $key) {
                        $query->where($key, $data[$key]);
                    }

                    $existing = $query->first();

                    if ($existing) {
                        Log::info("Existing record found");
                        $changes = [];
                        foreach ($data as $col => $newValue) {
                            $oldValue = $existing->$col;
                            if ($oldValue != $newValue) {
                                $changes[$col] = [
                                    'old' => $oldValue,
                                    'new' => $newValue,
                                ];
                            }
                        }

                        if ($changes) {
                            Log::info("Changes:", $changes);
                            foreach ($changes as $col => $values) {
                                AuditLog::create([
                                    'import_type' => $this->importType,
                                    'row_number' => $rowIndex + 1,
                                    'model' => $modelClass,
                                    'file_name' => $fileKey,
                                    'column_name' => $col,
                                    'table_name' => $modelClass,
                                    'old_value' => $values['old'],
                                    'new_value' => $values['new'],
                                ]);
                            }
                        }

                        $existing->update($data);
                    } else {
                        "App\\Models\\{$modelClass}"::create($data);
                    }
                } catch (\Exception $e) {
                    Log::error("Error durring import for row $rowIndex: " . $e->getMessage());
                    $errors[] = "Error durring import for row $rowIndex: " . $e->getMessage();
                    $importLog->update([
                        'status' => ImportLog::STATUS_UNSUCCESSFUL
                    ]);
                }
            }

            $this->user->notify(new ImportCompletedNotification($this->importType, $fileKey, count($errors)));
        }
    }
    protected function readFile(string $path, array $headers): array
    {

        $sheets = Excel::toArray([], $path);
        $rows = $sheets[0] ?? [];

        if (!empty($rows)) {
            $headers = array_keys($headers);
            $dataRows = array_slice($rows, 1);

            $assocRows = [];
            foreach ($dataRows as $row) {
                $assocRows[] = array_combine($headers, $row);
            }

            return $assocRows;
        }

        return [];
    }

    protected function flattenValidationRules(array $rules): array
    {
        $flat = [];

        foreach ($rules as $key => $value) {
            if (is_numeric($key)) {
                $flat[] = $value;
            } elseif ($key === 'in' && is_array($value)) {
                $flat[] = 'in:' . implode(',', $value);
            } elseif ($key === 'exists' && is_array($value)) {
                $flat[] = "exists:{$value['table']},{$value['column']}";
            } elseif ($key === 'unique' && is_array($value)) {
                $flat[] = "unique:{$value['table']},{$value['column']}";
            }
        }

        return $flat;
    }
}
