# TeamFlow SaaS

TeamFlow is a professional project and task management SaaS for high-performing teams, built with Laravel 12 + Inertia React + TypeScript + TailwindCSS.

## Highlights
- Multi-project collaboration with ownership + member assignment
- Task planning with Kanban drag-and-drop status updates
- Document collaboration and editing workspace
- Dashboard analytics (progress, workload, task states, recent activity)
- Role-based access control via Spatie Roles & Permissions
- Activity logging for auditability
- In-app database notifications for task updates
- Search and filtering across projects, tasks, and docs

## Tech Stack
- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** React + TypeScript + Inertia.js
- **Authorization:** `spatie/laravel-permission`
- **Testing:** Pest / PHPUnit

## Roles
- **Admin**: full CRUD + member/permission oversight
- **Member**: scoped project/task/doc access based on assignment and policy rules

## Core Modules
### Projects
- Full CRUD
- Member assignment
- Search filter
- Policy + service/repository architecture

### Tasks
- Full CRUD + status transitions
- Kanban board with drag-and-drop
- Status + search filters
- Assignment notifications + activity logging

### Documents
- Create/update/delete and detail view
- Search filter
- Policy enforcement + activity logging

### Dashboards
- Total projects/tasks
- Task status breakdown
- Workload distribution
- Project progress
- Recent activity stream

### Notifications
- Notification center page
- Mark-as-read flow
- Triggered by task create/update/status changes

### User Profile Summary
- Owned project count
- Assigned task count
- Activity count

## Architecture Notes
- SOLID-style layering: Controllers → Services → Repositories
- Validation through dedicated FormRequests
- Serialization via JsonResources
- Gate + Policy authorization for secured operations
- Extendable enums for roles and permissions

## Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## Test
```bash
php artisan test
```

## Main Routes
- `/dashboard`
- `/projects`
- `/tasks`
- `/docs`
- `/notifications`
- `/users`

## Contribution Guidelines
1. Create focused feature branches.
2. Keep changes modular (request/resource/service/repository/policy).
3. Add tests for every behavioral change.
4. Run linting and tests before opening PRs.
5. Document user-facing features in README when adding modules.

## Notes
- If your lockfile is stale for permissions package updates, run:
  - `composer update spatie/laravel-permission`
