<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    public function paginateForUser(User $user): LengthAwarePaginator
    {
        $query = Project::query()->with(['owner:id,name', 'members:id,name']);

        if (! $user->isAdmin()) {
            $query->where(function ($scopedQuery) use ($user): void {
                $scopedQuery->where('owner_id', $user->id)
                    ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('users.id', $user->id));
            });
        }

        return $query->latest()->paginate(10);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project->refresh();
    }

    public function syncMembers(Project $project, array $memberIds): void
    {
        $project->members()->sync($memberIds);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
