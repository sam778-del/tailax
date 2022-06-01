<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allPermissions = array(
            array(
                'name' => 'Edit Profile',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Change Password',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage User',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create User',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit User',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete User',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Branch',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Branch',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Branch',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Branch',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Measurement Field',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Measurement Field',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Measurement Field',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Measurement Field',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Currency',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Currency',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Currency',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Currency',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Tailor Product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Tailor Product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Tailor Product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Tailor Product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Production Stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Production Stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Production Stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Production Stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Fabric Size',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Fabric Size',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Fabric Size',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Fabric Size',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Manage Tailor Categories',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Create Tailor Categories',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Edit Tailor Categories',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name' => 'Delete Tailor Categories',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        );

        Permission::insert($allPermissions);

        $shop_owner_permissions = array(
            ["name" => "Edit Profile"],
            ["name" => "Change Password"],
            ["name" => "Manage User"],
            ["name" => "Create User"],
            ["name" => "Edit User"],
            ["name" => "Delete User"],
            ["name" => "Manage Tailor Product"],
            ["name" => "Create Tailor Product"],
            ["name" => "Edit Tailor Product"],
            ["name" => "Delete Tailor Product"],
            ["name" => "Manage Branch"],
            ["name" => "Create Branch"],
            ["name" => "Edit Branch"],
            ["name" => "Delete Branch"],
            ["name" => "Manage Currency"],
            ["name" => "Create Currency"],
            ["name" => "Edit Currency"],
            ["name" => "Delete Currency"],
            ["name" => "Manage Measurement Field"],
            ["name" => "Create Measurement Field"],
            ["name" => "Edit Measurement Field"],
            ["name" => "Delete Measurement Field"],
            ["name" => "Manage Production Stage"],
            ["name" => "Create Production Stage"],
            ["name" => "Edit Production Stage"],
            ["name" => "Delete Production Stage"],
            ["name" => "Manage Fabric Size"],
            ["name" => "Create Fabric Size"],
            ["name" => "Edit Fabric Size"],
            ["name" => "Delete Fabric Size"],
            ["name" => "Manage Tailor Categories"],
            ["name" => "Create Tailor Categories"],
            ["name" => "Edit Tailor Categories"],
            ["name" => "Delete Tailor Categories"],
        );

        $shop_owner = new User();
        $shop_owner->name = 'shop owner';
        $shop_owner->email = 'admin@example.com';
        $shop_owner->password = 1234;
        $shop_owner->parent_id = 0;
        $shop_owner->is_active = true;
        $shop_owner->user_status = true;
        $shop_owner->lang = 'en';
        $shop_owner->save();

        $shop_owner_role             = new Role();
        $shop_owner_role->name       = 'Shop Owner';
        $shop_owner_role->created_by = $shop_owner->id;
        $shop_owner_role->save();

        $shop_owner_role ->givePermissionTo($shop_owner_permissions);



        $shop_owner->assignRole($shop_owner_role);

        $branch = new Branch();
        $branch->name = 'Default';
        $branch->created_by = $shop_owner->id;
        $branch->save();
    }
}
