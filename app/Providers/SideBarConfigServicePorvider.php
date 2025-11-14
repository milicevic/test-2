<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SideBarConfigServicePorvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $imports = config('import_data', []);

        $importMenu = [
            [
                'text' => 'Data Imports',
                'icon' => 'fas fa-fw fa-upload',
                'submenu' => []
            ]
        ];

        foreach ($imports as $key => $import) {
            foreach ($import['files'] as $fileKey => $fileConfig) {
                $importMenu[0]['submenu'][] = [
                    'text' => $import['label'] . ' - ' . $fileConfig['label'],
                    'url'  => 'imports/' . $key . '/' . $fileKey,
                    'icon' => 'fas fa-file-import',
                    'can'  => $fileConfig['permission_required'] ?? null,
                ];
            }
        }
        $config = config('adminlte.menu',[]);

        array_splice($config, count($config) - 1, 0, $importMenu);
    
        config([
            'adminlte.menu' => $config
        ]);
    }
}
