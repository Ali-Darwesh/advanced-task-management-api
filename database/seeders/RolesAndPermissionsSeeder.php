<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $developerRole = Role::create(['name' => 'developer']);

        // Create permissions
        $assignRole = Permission::create(['name' => 'assign-role']);
        $createTask = Permission::create(['name' => 'create-task']);
        $assignTask = Permission::create(['name' => 'assign-task']);
        $updateTaskStatus = Permission::create(['name' => 'update-task-status']);
        $addComment = Permission::create(['name' => 'add-comment']);
        $addAttachment = Permission::create(['name' => 'add-attachment']);

        // Assign permissions to roles
        $adminRole->permissions()->attach([$assignRole->id, $addComment->id, $addAttachment->id]);
        $adminRole->permissions()->attach([$createTask->id]);
        $managerRole->permissions()->attach([$assignTask->id, $addComment->id, $addAttachment->id]);
        $developerRole->permissions()->attach([$updateTaskStatus->id, $addComment->id, $addAttachment->id]);
    }
}
