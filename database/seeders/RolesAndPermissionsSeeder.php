<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Permission::values() as $permission) {
            SpatiePermission::findOrCreate($permission, 'web');
        }

        $adminRole = SpatieRole::findOrCreate(Role::Admin->value, 'web');
        $memberRole = SpatieRole::findOrCreate(Role::Member->value, 'web');

        $adminRole->syncPermissions(Permission::values());

        $memberRole->syncPermissions([
            Permission::ProjectView->value,
            Permission::TaskView->value,
            Permission::TaskUpdate->value,
            Permission::DocView->value,
            Permission::DocUpdate->value,
        ]);
    }
}
