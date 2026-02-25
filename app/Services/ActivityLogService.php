<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(User $user, string $action, string $description, ?Model $subject = null, array $metadata = []): void
    {
        ActivityLog::query()->create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'metadata' => $metadata,
        ]);
    }
}
