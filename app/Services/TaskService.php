<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskUpdatedNotification;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function listForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->taskRepository->paginateForUser($user, $filters);
    }

    public function create(User $user, array $data): Task
    {
        $project = Project::query()->findOrFail($data['project_id']);

        if (! empty($data['assigned_to']) && ! $project->members()->where('users.id', $data['assigned_to'])->exists() && $project->owner_id !== (int) $data['assigned_to']) {
            $data['assigned_to'] = null;
        }

        $task = $this->taskRepository->create($data)->load(['project:id,name', 'assignee:id,name,email']);

        $this->activityLogService->log($user, 'task.created', 'Created task '.$task->title, $task);
        $task->assignee?->notify(new TaskUpdatedNotification($task, 'created'));

        return $task;
    }

    public function update(User $user, Task $task, array $data): Task
    {
        $updated = $this->taskRepository->update($task, $data)->load(['project:id,name', 'assignee:id,name,email']);

        $this->activityLogService->log($user, 'task.updated', 'Updated task '.$updated->title, $updated);
        $updated->assignee?->notify(new TaskUpdatedNotification($updated, 'updated'));

        return $updated;
    }

    public function updateStatus(User $user, Task $task, string $status): Task
    {
        $updated = $this->taskRepository->update($task, ['status' => $status])->load(['project:id,name', 'assignee:id,name,email']);

        $this->activityLogService->log($user, 'task.status_updated', 'Changed task status to '.$status, $updated, ['status' => $status]);
        $updated->assignee?->notify(new TaskUpdatedNotification($updated, 'status_updated'));

        return $updated;
    }

    public function delete(User $user, Task $task): void
    {
        $this->activityLogService->log($user, 'task.deleted', 'Deleted task '.$task->title, $task);
        $this->taskRepository->delete($task);
    }
}
