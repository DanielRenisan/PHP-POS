<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StationPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'station.view',
            'station.create',
            'station.update',
            'station.delete',
            'station.display.view',
            'station.ticket.reprint',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Grant to admin/superadmin if those roles exist
        foreach (['admin', 'super-admin', 'superadmin', 'Admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }
}
