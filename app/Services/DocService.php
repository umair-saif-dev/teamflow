<?php

namespace App\Services;

use App\Models\Doc;
use App\Models\User;
use App\Repositories\DocRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocService
{
    public function __construct(private readonly DocRepository $docRepository) {}

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $this->docRepository->paginateForUser($user);
    }

    public function create(User $user, array $data): Doc
    {
        return $this->docRepository->create([
            ...$data,
            'created_by' => $user->id,
        ])->load(['project:id,name', 'author:id,name,email']);
    }

    public function update(Doc $doc, array $data): Doc
    {
        return $this->docRepository->update($doc, $data)->load(['project:id,name', 'author:id,name,email']);
    }

    public function delete(Doc $doc): void
    {
        $this->docRepository->delete($doc);
    }
}
