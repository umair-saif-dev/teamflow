<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    public function paginateForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Project::query()->with(['owner:id,name', 'members:id,name']);

        if (! $user->isAdmin()) {
            $query->where(function ($scopedQuery) use ($user): void {
                $scopedQuery->where('owner_id', $user->id)
                    ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('users.id', $user->id));
            });
        }

        if (! empty($filters['search'])) {
            $search = strtolower((string) $filters['search']);
            $query->where(fn ($q) => $q
                ->whereRaw('LOWER(name) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(description) like ?', ["%{$search}%"]));
        }

        return $query->latest()->paginate(10)->withQueryString();
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
