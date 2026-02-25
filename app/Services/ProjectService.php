<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function listForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->projectRepository->paginateForUser($user, $filters);
    }

    public function create(User $user, array $data): Project
    {
        $project = $this->projectRepository->create([
            ...$data,
            'owner_id' => $user->id,
        ]);

        $this->projectRepository->syncMembers($project, [$user->id, ...($data['member_ids'] ?? [])]);

        $this->activityLogService->log($user, 'project.created', 'Created project '.$project->name, $project);

        return $project->load(['owner:id,name', 'members:id,name']);
    }

    public function update(User $user, Project $project, array $data): Project
    {
        $updatedProject = $this->projectRepository->update($project, $data);

        if (array_key_exists('member_ids', $data)) {
            $memberIds = array_values(array_unique([$updatedProject->owner_id, ...$data['member_ids']]));
            $this->projectRepository->syncMembers($updatedProject, $memberIds);
        }

        $this->activityLogService->log($user, 'project.updated', 'Updated project '.$updatedProject->name, $updatedProject);

        return $updatedProject->load(['owner:id,name', 'members:id,name']);
    }

    public function delete(User $user, Project $project): void
    {
        $this->activityLogService->log($user, 'project.deleted', 'Deleted project '.$project->name, $project);
        $this->projectRepository->delete($project);
    }
}
