<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            'product.create',
            'product.view',
            'product.update',
            'product.delete',
            'order.create',
            'order.view',
            'order.update',
            'order.delete',
            'user.create',
            'user.view',
            'user.update',
            'user.delete',
            'role.create',
            'role.view',
            'role.update',
            'role.delete',
            'permission.create',
            'permission.view',
            'permission.update',
            'permission.delete',
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $shopOwnerRole = Role::firstOrCreate(['name' => 'shop_owner']);

        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole->givePermissionTo([
            'product.create',
            'product.view',
            'product.update',
            'product.delete',
            'order.view',
            'order.update',
            'view dashboard',
        ]);

        $shopOwnerRole->givePermissionTo([
            'product.create',
            'product.view',
            'product.update',
            'order.view',
            'order.update',
            'view dashboard',
        ]);

        // Assign roles to users (optional)
        $user = User::first();
        if ($user) {
            $user->assignRole('super_admin');
        }
    }
}
