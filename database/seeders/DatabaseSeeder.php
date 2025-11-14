<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //test user and permissions
        $user = User::factory()->create([
            'name' => 'Test Ussser',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        $permissions = collect([
            ['name' => 'user-management', 'label' => 'User Management'],
            ['name' => 'upload-data', 'label' => 'Upload Data'],
        ])->map(function ($data) {
            return Permission::factory()->create($data);
        });

        $user->permissions()->sync($permissions->pluck('id'));
    }
}
