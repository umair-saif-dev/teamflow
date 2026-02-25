<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Task::class);

        return Inertia::render('tasks/index', [
            'tasks' => TaskResource::collection($this->taskService->listForUser($request->user(), $request->only(['search', 'status']))),
            'projects' => Project::query()->select('id', 'name')->orderBy('name')->get(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        $this->taskService->create($request->user(), $request->validated());

        return back()->with('success', 'Task created successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $this->taskService->update($request->user(), $task, $request->validated());

        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $this->taskService->delete($request->user(), $task);

        return back()->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', 'in:todo,in_progress,review,done'],
        ]);

        $this->taskService->updateStatus($request->user(), $task, $validated['status']);

        return back()->with('success', 'Task status updated successfully.');
    }
}
