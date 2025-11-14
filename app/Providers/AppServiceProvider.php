<?php

namespace App\Providers;

use App\Policies\UploadPolicy;
use App\Repositories\ImportRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // SideBarConfigServicePorvider::class;
        $this->app->bind(
            \App\Repositories\ImportRepositoryInterface::class,
            \App\Repositories\ImportRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('can', function ($user, $permissionName) {
            if (!$user->relationLoaded('permissions')) {
                $user->load('permissions');
            }

            return $user->permissions->contains('name', $permissionName);
        });

        Gate::define('upload-data', [UploadPolicy::class, 'upload']);

        Gate::define('user-management', function ($user) {
            return Gate::allows('can', 'user-management');
        });
    }
}
