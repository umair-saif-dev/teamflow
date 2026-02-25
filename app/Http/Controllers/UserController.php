<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        Gate::authorize(Permission::UserViewAny->value);

        $currentUser = request()->user();

        return Inertia::render('users/index', [
            'users' => User::query()->select('id', 'name', 'email')->latest()->paginate(20),
            'profileSummary' => [
                'projects' => Project::query()->where('owner_id', $currentUser->id)->count(),
                'assignedTasks' => Task::query()->where('assigned_to', $currentUser->id)->count(),
                'activities' => ActivityLog::query()->where('user_id', $currentUser->id)->count(),
            ],
        ]);
    }
}
