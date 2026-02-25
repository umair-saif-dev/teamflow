<?php

use App\Enums\Role;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('allows admin to create and move task status', function (): void {
    $admin = User::factory()->create(['role' => Role::Admin->value]);
    $admin->assignRole(Role::Admin->value);

    $project = Project::query()->create([
        'name' => 'Ops',
        'description' => 'Ops project',
        'owner_id' => $admin->id,
    ]);

    $project->members()->attach($admin->id);

    $createResponse = $this->actingAs($admin)->post('/tasks', [
        'project_id' => $project->id,
        'assigned_to' => $admin->id,
        'title' => 'Prepare sprint plan',
        'description' => 'Draft planning notes',
        'status' => 'todo',
        'priority' => 'high',
    ]);

    $createResponse->assertRedirect();

    $task = Task::query()->firstOrFail();

    $statusResponse = $this->actingAs($admin)->patch('/tasks/'.$task->id.'/status', [
        'status' => 'in_progress',
    ]);

    $statusResponse->assertRedirect();
    expect($task->fresh()->status)->toBe('in_progress');
});
