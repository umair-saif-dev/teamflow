<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $this->taskRepository->paginateForUser($user);
    }

    public function create(array $data): Task
    {
        $project = Project::query()->findOrFail($data['project_id']);

        if (! empty($data['assigned_to']) && ! $project->members()->where('users.id', $data['assigned_to'])->exists() && $project->owner_id !== (int) $data['assigned_to']) {
            $data['assigned_to'] = null;
        }

        return $this->taskRepository->create($data)->load(['project:id,name', 'assignee:id,name,email']);
    }

    public function update(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data)->load(['project:id,name', 'assignee:id,name,email']);
    }

    public function updateStatus(Task $task, string $status): Task
    {
        return $this->taskRepository->update($task, ['status' => $status])->load(['project:id,name', 'assignee:id,name,email']);
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }
}
