<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Project::class);

        return Inertia::render('projects/index', [
            'projects' => ProjectResource::collection($this->projectService->listForUser(request()->user())),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Project::class);

        return Inertia::render('projects/create', [
            'members' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $project = $this->projectService->create($request->user(), $request->validated());

        return to_route('projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(Project $project): Response
    {
        Gate::authorize('view', $project);

        return Inertia::render('projects/show', [
            'project' => new ProjectResource($project->load(['owner:id,name,email', 'members:id,name,email', 'tasks', 'docs'])),
        ]);
    }

    public function edit(Project $project): Response
    {
        Gate::authorize('update', $project);

        return Inertia::render('projects/edit', [
            'project' => new ProjectResource($project->load(['members:id,name'])),
            'members' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $this->projectService->update($project, $request->validated());

        return to_route('projects.show', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $this->projectService->delete($project);

        return to_route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
