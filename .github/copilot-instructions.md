# LGPDGo - AI Coding Agent Instructions

## Project Overview

**LGPDGo** is a GDPR/LGPD compliance management system built with Laravel 12 + Inertia.js + Vue.js 3, featuring multi-tenant architecture, role-based access control, and permission management.

## Tech Stack & Key Commands

**Backend**: Laravel 12, PHP 8.2+, MySQL/MariaDB, Sanctum authentication  
**Frontend**: Vue 3, Inertia.js, Tailwind CSS, Vite  
**Key Commands**:

-   `composer run dev` - Start development server (runs Laravel server + queue + logs + Vite concurrently)
-   `npm run dev` - Compile assets with Vite in watch mode
-   `php artisan migrate --seed` - Run migrations and seeders
-   `php artisan tinker` - Interactive shell for debugging
-   Setup: `composer run setup` - One-command installation

## Architecture & Data Flow

### Multi-Tenancy Pattern

-   **Company** model isolates data: Users belong to `Company`, accessed via `company_id` foreign key
-   **TenantScope middleware** (`app/Http/Middleware/TenantScope.php`): Enforces isolation except for superusers
-   Superuser (`is_super_user = true`) can access all companies; normal users restricted to their `company_id`
-   When fetching related data, always filter by authenticated user's company unless explicitly all-access

### Authentication & Authorization Flow

1. **Sanctum**: Token-based API auth, configured in `config/sanctum.php`
2. **Permission System**: Three-level hierarchy:
    - Individual permissions (slug-based, e.g., `manage-users`)
    - Permission groups (collections of permissions)
    - User-Permission mappings (via `user_permissions` table)
3. **Shared Props via Inertia**: `auth.user` automatically passed to frontend by `HandleInertiaRequests` middleware
4. **Authorization Middleware**:
    - `permission:slug` - Check single permission; accepts multiple slugs as arguments
    - `super_user` - Restrict to superusers only
    - Example: `Route::middleware(['auth', 'permission:manage-users'])->group(...)`

### Models & Relationships

-   **User**: `belongsTo(Company)`, `belongsToMany(Permission)`, `belongsToMany(PermissionGroup)`
-   **Company**: `hasMany(User)`, `softDeletes()` enabled
-   **Permission**: `belongsToMany(User)`, `belongsToMany(PermissionGroup)`
-   **PermissionGroup**: `belongsToMany(Permission)`
-   **Trait**: `BelongsToCompany.php` for automatic company scoping on models

## Frontend Architecture

### Layouts & Page Structure

-   **Inertia Pages** stored in `resources/js/Pages/`:
    -   Routes auto-map: `Dashboard` → `/dashboard`, nested paths use subdirectories
    -   Pages automatically resolve layouts (e.g., `Dashboard.vue` uses `AuthenticatedLayout`)
-   **Layouts**: `resources/js/Layouts/AuthenticatedLayout.vue` wraps authenticated pages with navbar/sidebar
-   **Components**: Reusable UI in `resources/js/Components/`

### Data Passing & Reactivity

-   Backend returns props via `Inertia::render('Dashboard', ['data' => ...])` in routes
-   Frontend accesses via `usePage()` hook: `const page = usePage()`, then `page.props.auth.user`
-   Use `computed()` to reactively track prop changes
-   Props are automatically available in template with `{{ }}` binding

### Styling Convention

-   **Tailwind CSS** classes directly in templates
-   Common pattern: `bg-white rounded-3xl border border-slate-200 shadow-sm p-8`
-   Color scale: `slate-50` → `slate-900`, with `indigo-600` for primary actions
-   Responsive: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4` pattern

## Critical Developer Workflows

### Adding a New Permission-Protected Feature

1. Create migration: `php artisan make:migration create_[feature]_permission --table=permissions`
2. Add permission via seeder in `database/seeders/InitialDataSeeder.php`
3. Add route middleware: `Route::middleware('permission:feature-slug')->...`
4. Frontend checks permission before showing UI: conditional rendering with `v-if="user?.permissions.includes('feature-slug')"`

### Creating New Page/Form

1. Create controller: `php artisan make:controller [Resource]Controller -r`
2. Create Vue component: `resources/js/Pages/[Resource]/Index.vue`
3. Return from route: `Inertia::render('[Resource]/Index', ['data' => ...])`
4. Use `AuthenticatedLayout` or create custom layout extending it

### Adding Company-Scoped Feature

1. Use `BelongsToCompany` trait on model or manually add scope
2. Apply `TenantScope` middleware on routes
3. Frontend gets company via `usePage().props.auth.user.company`
4. Filter API calls by `?company_id=` when needed

## Conventions & Patterns

**File Organization**:

-   Controllers: `app/Http/Controllers/` - One per resource
-   Models: `app/Models/` - Include relationships, casts, fillables
-   Migrations: `database/migrations/` - Timestamp-prefixed, reversible
-   Vue Pages: `resources/js/Pages/` - PascalCase, mirror DB resources
-   Traits: `app/Traits/` - Reusable model behaviors (e.g., `BelongsToCompany`)

**Naming Conventions**:

-   Routes: snake_case slug (e.g., `dashboard`, `manage-users`)
-   Permission slugs: kebab-case (e.g., `view-reports`, `delete-users`)
-   Model methods: camelCase, boolean prefixes (`is`, `has`, `can`)
-   Vue components: PascalCase

**Error Handling**:

-   Authorization failures: `abort(403)` with descriptive message
-   Validation: Form requests in `app/Http/Requests/` (not shown but standard Laravel)
-   User feedback: Inertia preserves `flash()` messages across redirects

**Database Patterns**:

-   Timestamps: All tables auto-include `created_at`, `updated_at`
-   Soft deletes: Use `SoftDeletes` trait for safe removal (e.g., `Company`)
-   Foreign keys: Name as `{model}_id`, add `constrained()` in migration

## Testing & Debugging

-   Run tests: `php artisan test`
-   Database reset: `php artisan migrate:fresh --seed`
-   Check queue: Monitor via `php artisan pail` (logs real-time)
-   Default superuser: Email `dev@lgpdgo.com`, Password `password`
-   Debug permission: `auth()->user()->hasPermission('slug')` in tinker

## External Resources & Dependencies

-   Laravel docs: https://laravel.com/docs
-   Inertia.js: Server-side rendering bridge, handles prop passing
-   Vue 3 Composition API: `<script setup>`, `computed()`, `ref()`
-   Ziggy: URL route helper in frontend (imported from vendor)
