<?php

namespace Jaffran\LaravelTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdatePermissionRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jaffran-permission-role:sync {guard=web}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync role permissions based on the config file for the specified guard';

    /**
     * All Roles in database
     *
     * @var Collection
     */
    protected $allRoles;

    /**
     * All permissions in database
     *
     * @var Collection
     */
    protected $allPermissions;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the guard from the command argument
        $guard = $this->argument('guard');

        $permissions = config('LaravelTools.available_permissions');

        $this->allPermissions = Permission::where('guard_name', $guard)->get();
        $this->allRoles = Role::where('guard_name', $guard)->get();

        // Insert missing roles for the given guard
        $this->insertMissingRoles($permissions, $guard);

        // Refresh roles
        $this->allRoles = Role::where('guard_name', $guard)->get();

        foreach ($permissions as $permission) {
            $permissionModel = $this->allPermissions->where('name', $permission['name'])->first();

            if (!$permissionModel) {
                $this->line("Permission not found, creating permission '{$permission['name']}' for guard '{$guard}'...");
                $permissionModel = Permission::create(['name' => $permission['name'], 'guard_name' => $guard]);
                $this->line('Permission created!');
            }

            $roles = $permission['roles'];
            $roleModels = $this->allRoles->whereIn('name', $roles)->pluck('name')->toArray();

            if ($roleModels == null) {
                $this->warn("Role not found when searching for permission '{$permission['name']}'");
            }

            $this->line("Syncing roles for permission '{$permissionModel->name}' for guard '{$guard}'");
            $permissionModel->syncRoles($roleModels);
        }

        $this->info('Roles and permissions synced successfully!');
        return Command::SUCCESS;
    }

    /**
     * Insert missing roles for the given guard.
     *
     * @param array $configRoles
     * @param string $guard
     * @return void
     */
    protected function insertMissingRoles(array $configRoles, string $guard): void
    {
        $missingRoles = collect($configRoles)->pluck('roles')->flatten()->unique()->diff(
            $this->allRoles->pluck('name')
        );

        if ($missingRoles->count() > 0) {
            $newRoles = [];
            foreach ($missingRoles as $missingRole) {
                $this->line("Found new role '{$missingRole}' for guard '{$guard}'");
                $newRoles[] = [
                    'name' => $missingRole,
                    'guard_name' => $guard,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Role::insert($newRoles);
            $this->info("Added new " . count($newRoles) . " roles for guard '{$guard}'");
        }
    }
}
