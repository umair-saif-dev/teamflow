<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        Gate::authorize(Permission::UserViewAny->value);

        return Inertia::render('users/index', [
            'users' => User::query()->select('id', 'name', 'email')->latest()->paginate(20),
        ]);
    }
}
