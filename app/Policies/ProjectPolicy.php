<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProjectViewAny->value);
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->hasPermissionTo(Permission::ProjectViewAny->value)) {
            return true;
        }

        return $user->hasPermissionTo(Permission::ProjectView->value)
            && (
                $project->owner_id === $user->id
                || $project->members()->where('users.id', $user->id)->exists()
            );
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProjectCreate->value);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasPermissionTo(Permission::ProjectUpdate->value)
            && (
                $user->hasPermissionTo(Permission::ProjectViewAny->value)
                || $project->owner_id === $user->id
            );
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasPermissionTo(Permission::ProjectDelete->value)
            && (
                $user->hasPermissionTo(Permission::ProjectViewAny->value)
                || $project->owner_id === $user->id
            );
    }
}
