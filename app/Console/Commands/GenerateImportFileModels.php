<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class GenerateImportFileModels extends Command
{
    protected $signature = 'import:generate-file-models';
    protected $description = 'Generate models and migrations for each unique file in imports config';

    protected Filesystem $files;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    public function handle()
    {
        $importTypes = config('import_data', []);

        foreach ($importTypes as $importKey => $importConfig) {
            foreach ($importConfig['files'] as $fileKey => $fileConfig) {

                $tableName = $importKey . '_' . $fileKey;
                $modelName = Str::studly($importKey) . Str::studly($fileKey);

                $this->call('make:model', [
                    'name' => $modelName
                ]);
                $this->info("Model $modelName created.");

                $this->call('make:migration', [
                    'name' => "create_{$tableName}_table",
                    '--create' => $tableName,
                ]);

                $this->info("Migration for $tableName created.");

                $this->populateMigrationColumns($tableName, $fileConfig);
            }
        }

        $this->info('All models and migrations for each unique file generated successfully.');
    }

    protected function populateMigrationColumns(string $tableName, array $fileConfig)
    {
        $columns = $fileConfig['headers_to_db'] ?? [];


        $migrationFile = collect($this->files->files(database_path('migrations')))
            ->filter(fn($file) => str_contains($file->getFilename(), "create_{$tableName}_table"))
            ->first();

        if (!$migrationFile) return;

        $content = $this->files->get($migrationFile->getPathname());

        $columnsCode = "";
        foreach ($columns as $column => $meta) {
            $type = $meta['type'] ?? 'string';
            switch ($type) {
                case 'string':
                    $columnsCode .= "\$table->string('$column')->nullable();\n            ";
                    break;
                case 'double':
                    $columnsCode .= "\$table->double('$column', 8, 2)->nullable();\n            ";
                    break;
                case 'date':
                    $columnsCode .= "\$table->date('$column')->nullable();\n            ";
                    break;
                default:
                    $columnsCode .= "\$table->string('$column')->nullable();\n            ";
            }
        }

        // Insert before timestamps
        $content = preg_replace(
            '/\$table->timestamps\(\);/',
            $columnsCode . "\$table->timestamps();",
            $content
        );

        $this->files->put($migrationFile->getPathname(), $content);
        $this->info("Migration columns for $tableName populated.");
    }
}
