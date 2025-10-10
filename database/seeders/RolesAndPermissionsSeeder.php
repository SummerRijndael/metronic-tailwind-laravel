<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;

class RolesAndPermissionsSeeder extends Seeder {
    public function run() {
        $roles = Config::get('permissions.roles');
        $permissions = Config::get('permissions.permissions');

        if (!$roles || !$permissions) {
            $this->command->error('⚠️ No roles or permissions found in config/permissions.php');
            return;
        }

        // 🧱 Create all permissions first
        foreach ($permissions as $key => $desc) {
            Permission::firstOrCreate(['name' => $key]); // description removed
        }

        // 🧩 Then create roles and attach permissions
        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Ensure all permissions exist before syncing
            $validPerms = Permission::whereIn('name', $perms)->pluck('name')->toArray();
            $role->syncPermissions($validPerms);
        }

        $this->command->info('✅ Roles and permissions seeded successfully!');
    }
}
