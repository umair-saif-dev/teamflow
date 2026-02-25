<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Doc;
use App\Models\User;

class DocPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DocViewAny->value)
            || $user->hasPermissionTo(Permission::DocView->value);
    }

    public function view(User $user, Doc $doc): bool
    {
        if ($user->hasPermissionTo(Permission::DocViewAny->value)) {
            return true;
        }

        return $user->hasPermissionTo(Permission::DocView->value)
            && $user->belongsToProject($doc->project);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DocCreate->value);
    }

    public function update(User $user, Doc $doc): bool
    {
        return $user->hasPermissionTo(Permission::DocUpdate->value)
            && ($user->belongsToProject($doc->project) || $doc->created_by === $user->id);
    }

    public function delete(User $user, Doc $doc): bool
    {
        return $user->hasPermissionTo(Permission::DocDelete->value)
            && ($user->belongsToProject($doc->project) || $doc->created_by === $user->id);
    }
}
