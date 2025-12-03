<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"ontwikkelaar","guard_name":"web","permissions":["project-info-wijzigen","vrijwilligers-pagina-wijzigen","ontgrendel_resources","view-any:article-report","view:article-report","mark-in-progress:article-report","mark-as-closed:article-report","delete:article-report","delete-any:article-report","view:disclaimer","view-any:disclaimer","create:disclaimer","update:disclaimer","delete:disclaimer","delete-any:disclaimer","view:etymology","view-any:etymology","update:etymology","delete:etymology","delete-any:etymology","archive:etymology","reject:etymology","publish:etymology","draft:etymology","under-review:etymology","delete-any:label","detach:label","attach:label","create:label","delete:label","update:label","view:label","view-any:label","view-any:blog","view:blog","update:blog","delete:blog","delete-any:blog","undo-publication:blog","view:category","view-any:category","create:category","update:category","delete:category","delete-any:category","view-any:ban","view:ban","update:ban","delete:ban","update:article","send-for-approval:article","publish:article","unpublish:article","detach-editor:article","attach-disclaimer:article","detach-disclaimer:article","archive:article","unarchive:article","delete:article","delete-any:article","restore:article","restore-any:article","export:article","view-any:feedback","view:feedback","delete:feedback","delete-any:feedback","change-status:feedback","view-any:user","create:user","deactivate:user","deactivate-update:user","reactivate:user","view-any:role","view:role","create:role","update:role","delete:role","disable-comments:blog","enable-comments:blog","update-published:article"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
