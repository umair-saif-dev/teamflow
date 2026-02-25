<?php

use App\Enums\Role;
use App\Models\ActivityLog;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('logs project create activity', function (): void {
    $admin = User::factory()->create(['role' => Role::Admin->value]);
    $admin->assignRole(Role::Admin->value);

    $response = $this->actingAs($admin)->post('/projects', [
        'name' => 'Platform Revamp',
        'description' => 'Core system refresh',
        'member_ids' => [$admin->id],
    ]);

    $response->assertRedirect();

    expect(ActivityLog::query()->where('action', 'project.created')->exists())->toBeTrue();
});
