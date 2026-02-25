<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::TaskViewAny->value)
            || $user->hasPermissionTo(Permission::TaskView->value);
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->hasPermissionTo(Permission::TaskViewAny->value)) {
            return true;
        }

        return $user->hasPermissionTo(Permission::TaskView->value)
            && $user->belongsToProject($task->project);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::TaskCreate->value);
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->hasPermissionTo(Permission::TaskUpdate->value) && $user->belongsToProject($task->project)) {
            return true;
        }

        return $user->hasPermissionTo(Permission::TaskUpdate->value)
            && $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(Permission::TaskDelete->value)
            && $user->belongsToProject($task->project);
    }
}
