<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class MainPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create main permissions
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'list categories']);
        Permission::create(['name' => 'delete categories']);

        Permission::create(['name' => 'create coupons']);
        Permission::create(['name' => 'edit coupons']);
        Permission::create(['name' => 'list coupons']);
        Permission::create(['name' => 'delete coupons']);

        Permission::create(['name' => 'create pincodes']);
        Permission::create(['name' => 'edit pincodes']);
        Permission::create(['name' => 'list pincodes']);
        Permission::create(['name' => 'delete pincodes']);

        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'list products']);
        Permission::create(['name' => 'delete products']);

        Permission::create(['name' => 'list delivery charges']);
        Permission::create(['name' => 'save delivery charges']);

        Permission::create(['name' => 'list taxes']);
        Permission::create(['name' => 'save taxes']);

        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'list orders']);

    }
}
