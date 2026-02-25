<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'owner' => $this->whenLoaded('owner', fn (): array => [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
                'email' => $this->owner->email,
            ]),
            'members' => $this->relationLoaded('members')
                ? $this->members->map(fn ($member): array => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                ])->values()
                : [],
            'tasks' => $this->relationLoaded('tasks')
                ? $this->tasks->map(fn ($task): array => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                ])->values()
                : [],
            'docs' => $this->relationLoaded('docs')
                ? $this->docs->map(fn ($doc): array => [
                    'id' => $doc->id,
                    'title' => $doc->title,
                ])->values()
                : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
