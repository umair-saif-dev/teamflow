<?php

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\User;

it('allows admins to access project creation', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get('/projects/create');

    $response->assertOk();
});

it('forbids members from accessing project creation', function (): void {
    $member = User::factory()->create(['role' => UserRole::Member]);

    $response = $this->actingAs($member)->get('/projects/create');

    $response->assertForbidden();
});

it('allows assigned members to view a project', function (): void {
    $owner = User::factory()->create(['role' => UserRole::Admin]);
    $member = User::factory()->create(['role' => UserRole::Member]);

    $project = Project::query()->create([
        'name' => 'Website Redesign',
        'description' => 'Revamp landing page',
        'owner_id' => $owner->id,
    ]);

    $project->members()->attach($member->id);

    $response = $this->actingAs($member)->get('/projects/'.$project->id);

    $response->assertOk();
});
