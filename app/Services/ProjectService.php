<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(private readonly ProjectRepository $projectRepository) {}

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $this->projectRepository->paginateForUser($user);
    }

    public function create(User $user, array $data): Project
    {
        $project = $this->projectRepository->create([
            ...$data,
            'owner_id' => $user->id,
        ]);

        $this->projectRepository->syncMembers($project, [$user->id, ...($data['member_ids'] ?? [])]);

        return $project->load(['owner:id,name', 'members:id,name']);
    }

    public function update(Project $project, array $data): Project
    {
        $updatedProject = $this->projectRepository->update($project, $data);

        if (array_key_exists('member_ids', $data)) {
            $memberIds = array_values(array_unique([$updatedProject->owner_id, ...$data['member_ids']]));
            $this->projectRepository->syncMembers($updatedProject, $memberIds);
        }

        return $updatedProject->load(['owner:id,name', 'members:id,name']);
    }

    public function delete(Project $project): void
    {
        $this->projectRepository->delete($project);
    }
}
