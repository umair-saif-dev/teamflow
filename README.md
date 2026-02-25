# TeamFlow SaaS

TeamFlow is a project and task management SaaS built on Laravel 12 + Inertia React + TypeScript + TailwindCSS.

## Tech Stack
- **Backend:** Laravel 12, PHP 8.2+, MySQL/SQLite
- **Frontend:** React + TypeScript + Inertia.js
- **Authorization:** Spatie Roles & Permissions
- **Testing:** Pest / PHPUnit

## Implemented Features

### Access Control
- Spatie `roles` + `permissions` schema migration.
- Enum-based roles and permissions in code (`Role`, `Permission`).
- Seeded roles/permissions with admin/member defaults.
- Policy enforcement for Projects, Tasks, and Docs.

### Projects
- CRUD for projects.
- Project owner + member assignment (multi-select in create/edit pages).
- Scoped visibility for non-admin users.

### Tasks
- Task CRUD routes + status-update endpoint.
- Kanban-style task board with drag-and-drop status updates.
- Search/filter by task title.

### Docs
- Docs list, create, detail/edit flows.
- Policy-based viewing and editing.

### Dashboard
- Analytics endpoint/page with:
  - project count,
  - task count,
  - tasks per status,
  - project progress percentage.

### Users
- Admin-oriented users index page.

## Run Locally

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
- `/users`

## Notes
- `spatie/laravel-permission` is required in `composer.json`.
- If lockfile is stale, run `composer update spatie/laravel-permission` in a network-enabled environment.
