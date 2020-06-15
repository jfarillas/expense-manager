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
           'view_roles',
           'create_roles',
           'edit_roles',
           'delete_roles',
           'view_users',
           'create_users',
           'edit_users',
           'delete_users',
           'view_category',
           'create_category',
           'edit_category',
           'delete_category',
           'view_expenses',
           'create_expenses',
           'edit_expenses',
           'delete_expenses',
           'edit_password'
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}