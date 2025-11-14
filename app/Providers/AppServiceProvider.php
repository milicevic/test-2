<?php

namespace App\Providers;

use App\Policies\UploadPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        SideBarConfigServicePorvider::class;
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

        // Use the generic gate for your specific 'user-management' check
        Gate::define('user-management', function ($user) {
            return Gate::allows('can', 'user-management');
        });
    }
}
