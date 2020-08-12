<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'view_roles',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_roles',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit_roles',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_roles',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_users',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_users',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit_users',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_users',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_category',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_category',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit_category',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_category',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_expenses',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_expenses',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit_expenses',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_expenses',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit_password',
                'guard_name' => 'api'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}