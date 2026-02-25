<?php

namespace App\Services;

use App\Models\Doc;
use App\Models\User;
use App\Repositories\DocRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocService
{
    public function __construct(
        private readonly DocRepository $docRepository,
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function listForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->docRepository->paginateForUser($user, $filters);
    }

    public function create(User $user, array $data): Doc
    {
        $doc = $this->docRepository->create([
            ...$data,
            'created_by' => $user->id,
        ])->load(['project:id,name', 'author:id,name,email']);

        $this->activityLogService->log($user, 'doc.created', 'Created doc '.$doc->title, $doc);

        return $doc;
    }

    public function update(User $user, Doc $doc, array $data): Doc
    {
        $updated = $this->docRepository->update($doc, $data)->load(['project:id,name', 'author:id,name,email']);

        $this->activityLogService->log($user, 'doc.updated', 'Updated doc '.$updated->title, $updated);

        return $updated;
    }

    public function delete(User $user, Doc $doc): void
    {
        $this->activityLogService->log($user, 'doc.deleted', 'Deleted doc '.$doc->title, $doc);
        $this->docRepository->delete($doc);
    }
}
