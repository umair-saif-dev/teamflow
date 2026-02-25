<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('allows admins to access project creation', function (): void {
    $admin = User::factory()->create(['role' => Role::Admin->value]);
    $admin->assignRole(Role::Admin->value);

    $response = $this->actingAs($admin)->get('/projects/create');

    $response->assertOk();
});

it('forbids members from accessing project creation', function (): void {
    $member = User::factory()->create(['role' => Role::Member->value]);
    $member->assignRole(Role::Member->value);

    $response = $this->actingAs($member)->get('/projects/create');

    $response->assertForbidden();
});

it('allows assigned members with permission to view a project', function (): void {
    $owner = User::factory()->create(['role' => Role::Admin->value]);
    $owner->assignRole(Role::Admin->value);

    $member = User::factory()->create(['role' => Role::Member->value]);
    $member->assignRole(Role::Member->value);

    $project = Project::query()->create([
        'name' => 'Website Redesign',
        'description' => 'Revamp landing page',
        'owner_id' => $owner->id,
    ]);

    $project->members()->attach($member->id);

    $response = $this->actingAs($member)->get('/projects/'.$project->id);

    $response->assertOk();
});

it('forbids unassigned members even if they have project view permission', function (): void {
    $owner = User::factory()->create(['role' => Role::Admin->value]);
    $owner->assignRole(Role::Admin->value);

    $member = User::factory()->create(['role' => Role::Member->value]);
    $member->givePermissionTo(Permission::ProjectView->value);

    $project = Project::query()->create([
        'name' => 'Internal Ops',
        'description' => null,
        'owner_id' => $owner->id,
    ]);

    $response = $this->actingAs($member)->get('/projects/'.$project->id);

    $response->assertForbidden();
});
