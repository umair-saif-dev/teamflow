<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function paginateForUser(User $user): LengthAwarePaginator
    {
        $query = Task::query()->with(['project:id,name,owner_id', 'assignee:id,name,email']);

        if (! $user->isAdmin()) {
            $query->where(function ($scopedQuery) use ($user): void {
                $scopedQuery->where('assigned_to', $user->id)
                    ->orWhereHas('project.members', fn ($q) => $q->where('users.id', $user->id))
                    ->orWhereHas('project', fn ($q) => $q->where('owner_id', $user->id));
            });
        }

        return $query->latest()->paginate(15);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->refresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
