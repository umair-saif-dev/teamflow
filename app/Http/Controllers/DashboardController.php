<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        Gate::authorize(Permission::DashboardView->value);

        $totalProjects = Project::query()->count();
        $totalTasks = Task::query()->count();

        $tasksByStatus = Task::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $workloadByUser = Task::query()
            ->selectRaw('assigned_to, COUNT(*) as total')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->with('assignee:id,name')
            ->get()
            ->map(fn (Task $task): array => [
                'user' => $task->assignee?->name ?? 'Unassigned',
                'total' => (int) $task->total,
            ]);

        $recentActivity = ActivityLog::query()
            ->with('user:id,name')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (ActivityLog $log): array => [
                'id' => $log->id,
                'user' => $log->user?->name ?? 'System',
                'action' => $log->action,
                'description' => $log->description,
                'at' => $log->created_at,
            ]);

        $projectProgress = Project::query()
            ->withCount([
                'tasks as tasks_total',
                'tasks as tasks_done' => fn ($query) => $query->where('status', 'done'),
            ])
            ->get()
            ->map(fn (Project $project): array => [
                'id' => $project->id,
                'name' => $project->name,
                'progress' => $project->tasks_total > 0
                    ? (int) round(($project->tasks_done / $project->tasks_total) * 100)
                    : 0,
            ]);

        return Inertia::render('dashboard', [
            'analytics' => [
                'totalProjects' => $totalProjects,
                'totalTasks' => $totalTasks,
                'tasksByStatus' => $tasksByStatus,
                'workloadByUser' => $workloadByUser,
                'recentActivity' => $recentActivity,
                'projectProgress' => $projectProgress,
            ],
        ]);
    }
}
