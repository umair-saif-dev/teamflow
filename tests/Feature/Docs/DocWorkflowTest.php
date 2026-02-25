<?php

use App\Enums\Role;
use App\Models\Doc;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('allows admin to create and update a doc', function (): void {
    $admin = User::factory()->create(['role' => Role::Admin->value]);
    $admin->assignRole(Role::Admin->value);

    $project = Project::query()->create([
        'name' => 'Docs',
        'description' => 'Knowledge base',
        'owner_id' => $admin->id,
    ]);

    $project->members()->attach($admin->id);

    $createResponse = $this->actingAs($admin)->post('/docs', [
        'project_id' => $project->id,
        'title' => 'Onboarding',
        'content' => 'Initial guide',
    ]);

    $createResponse->assertRedirect('/docs');

    $doc = Doc::query()->firstOrFail();

    $updateResponse = $this->actingAs($admin)->put('/docs/'.$doc->id, [
        'title' => 'Onboarding Guide',
        'content' => 'Updated guide',
    ]);

    $updateResponse->assertRedirect();
    expect($doc->fresh()->title)->toBe('Onboarding Guide');
});
