# E-learning Laravel

Laravel modular monolith skeleton for the UML-defined e-learning platform.

## Phase 0 Scope

- Laravel-ready project shell inside `elearning-laravel`.
- Blade view structure.
- Vite asset entrypoints.
- MySQL/MariaDB-ready environment example.
- Test directory structure.

Phase 0 intentionally does not implement business features, domain models, migrations, authentication flows, or UML workflows.

## Intended Architecture

The application will remain a Laravel modular monolith. Future implementation should group code by module while staying inside the normal Laravel app:

- `Accounts`
- `Admin`
- `TrainerVerification`
- `Catalog`
- `Courses`
- `Enrollment`
- `Evaluations`
- `Certification`

## Local Setup

Install PHP, Composer, Node.js, and a MySQL/MariaDB server, then run:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials before running migrations.

