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

        $rolesWithPermissions = '[{"name":"ontwikkelaars","guard_name":"web","permissions":["update_article","send_for_approval_article","publish_article","unpublish_article","detach_editor_article","attach_disclaimer_article","detach_disclaimer_article","archive_article","unarchive_article","delete_article","delete_any_article","restore_article","restore_any_article","export_article","view_any_article::report","view_article::report","mark_in_progress_article::report","mark_as_closed_article::report","delete_article::report","delete_any_article::report","view_any_ban","view_ban","update_ban","delete_ban","view_any_blog","view_blog","update_blog","delete_blog","delete_any_blog","undo_publication_blog","view_category","view_any_category","create_category","update_category","delete_category","delete_any_category","view_disclaimer","view_any_disclaimer","create_disclaimer","update_disclaimer","delete_disclaimer","delete_any_disclaimer","view_etymology","view_any_etymology","update_etymology","delete_etymology","delete_any_etymology","archive_etymology","reject_etymology","publish_etymology","draft_etymology","under_review_etymology","view_any_feedback","view_feedback","delete_feedback","delete_any_feedback","delete_any_label","detach_label","attach_label","create_label","delete_label","update_label","view_label","view_any_label","unlock_resource_lock","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_any_user","create_user","deactivate_user","deactivate_update_user","reactivate_user","page_ProjectInformationSettings","page_VolunteerCallOutSettings","page_Articles","page_Blog","page_UserManagement"]}]';
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
