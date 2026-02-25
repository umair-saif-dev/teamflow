<?php

namespace App\Http\Controllers;

use App\Http\Requests\Docs\StoreDocRequest;
use App\Http\Requests\Docs\UpdateDocRequest;
use App\Http\Resources\DocResource;
use App\Models\Doc;
use App\Models\Project;
use App\Services\DocService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DocController extends Controller
{
    public function __construct(private readonly DocService $docService) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Doc::class);

        return Inertia::render('docs/index', [
            'docs' => DocResource::collection($this->docService->listForUser($request->user(), $request->only(['search']))),
            'projects' => Project::query()->select('id', 'name')->orderBy('name')->get(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(Doc $doc): Response
    {
        Gate::authorize('view', $doc);

        return Inertia::render('docs/show', [
            'doc' => new DocResource($doc->load(['project:id,name', 'author:id,name,email'])),
        ]);
    }

    public function store(StoreDocRequest $request): RedirectResponse
    {
        Gate::authorize('create', Doc::class);

        $this->docService->create($request->user(), $request->validated());

        return to_route('docs.index')->with('success', 'Document created successfully.');
    }

    public function update(UpdateDocRequest $request, Doc $doc): RedirectResponse
    {
        Gate::authorize('update', $doc);

        $this->docService->update($request->user(), $doc, $request->validated());

        return back()->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, Doc $doc): RedirectResponse
    {
        Gate::authorize('delete', $doc);

        $this->docService->delete($request->user(), $doc);

        return to_route('docs.index')->with('success', 'Document deleted successfully.');
    }
}
