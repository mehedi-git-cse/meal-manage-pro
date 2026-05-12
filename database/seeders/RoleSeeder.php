<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'super_admin',
                'display_name' => 'Super Admin',
                'color'        => '#ef4444',
                'guard_name'   => 'web',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'manager',
                'display_name' => 'Manager',
                'color'        => '#8b5cf6',
                'guard_name'   => 'web',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'staff',
                'display_name' => 'Staff',
                'color'        => '#3b82f6',
                'guard_name'   => 'web',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name'], 'guard_name' => 'web'],
                $role
            );
        }

        // Seed permissions
        $permissions = [
            'view meals', 'create meals', 'edit meals', 'delete meals',
            'view bazar', 'create bazar', 'edit bazar', 'delete bazar', 'verify bazar',
            'view costs', 'calculate costs', 'finalize costs',
            'view reports', 'export reports',
            'view users', 'create users', 'edit users', 'delete users',
            'view settings', 'edit settings',
        ];

        foreach ($permissions as $permission) {
            $displayName = ucwords(str_replace(['.', '_'], ' ', $permission));
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web', 'display_name' => $displayName, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Assign all permissions to super_admin
        $superAdmin = DB::table('roles')->where('name', 'super_admin')->first();
        $allPermIds = DB::table('permissions')->pluck('id');
        foreach ($allPermIds as $permId) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['permission_id' => $permId, 'role_id' => $superAdmin->id]
            );
        }

        // Assign limited permissions to manager
        $manager = DB::table('roles')->where('name', 'manager')->first();
        $managerPerms = DB::table('permissions')
            ->whereNotIn('name', ['view settings', 'edit settings', 'delete users'])
            ->pluck('id');
        foreach ($managerPerms as $permId) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['permission_id' => $permId, 'role_id' => $manager->id]
            );
        }

        // Staff gets basic permissions
        $staff = DB::table('roles')->where('name', 'staff')->first();
        $staffPerms = DB::table('permissions')
            ->whereIn('name', ['view meals', 'create meals', 'view bazar', 'create bazar', 'view reports'])
            ->pluck('id');
        foreach ($staffPerms as $permId) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['permission_id' => $permId, 'role_id' => $staff->id]
            );
        }
    }
}
