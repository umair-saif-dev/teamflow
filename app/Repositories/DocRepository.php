<?php

namespace App\Repositories;

use App\Models\Doc;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocRepository
{
    public function paginateForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Doc::query()->with(['project:id,name,owner_id', 'author:id,name,email']);

        if (! $user->isAdmin()) {
            $query->where(function ($scopedQuery) use ($user): void {
                $scopedQuery->where('created_by', $user->id)
                    ->orWhereHas('project.members', fn ($q) => $q->where('users.id', $user->id))
                    ->orWhereHas('project', fn ($q) => $q->where('owner_id', $user->id));
            });
        }

        if (! empty($filters['search'])) {
            $search = strtolower((string) $filters['search']);
            $query->where(fn ($q) => $q
                ->whereRaw('LOWER(title) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(content) like ?', ["%{$search}%"]));
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data): Doc
    {
        return Doc::create($data);
    }

    public function update(Doc $doc, array $data): Doc
    {
        $doc->update($data);

        return $doc->refresh();
    }

    public function delete(Doc $doc): void
    {
        $doc->delete();
    }
}
