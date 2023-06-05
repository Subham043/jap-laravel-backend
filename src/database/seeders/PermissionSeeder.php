<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create common permissions
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'delete roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'delete enquiries']);
        Permission::create(['name' => 'list enquiries']);

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        Role::create(['name' => 'Super-Admin']);

        // create roles and assign created permissions
        Role::create(['name' => 'staff'])
            ->givePermissionTo(['list users', 'list roles']);

    }
}
