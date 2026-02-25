<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Models\Doc;
use App\Models\Project;
use App\Models\Task;
use App\Policies\DocPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureDefaults();

        JsonResource::withoutWrapping();

        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Doc::class, DocPolicy::class);

        Gate::define(Permission::DashboardView->value, fn ($user): bool => $user->hasPermissionTo(Permission::DashboardView->value));
        Gate::define(Permission::UserViewAny->value, fn ($user): bool => $user->hasPermissionTo(Permission::UserViewAny->value));
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
