# E-learning Laravel

Laravel Blade modular monolith for the UML-defined e-learning platform.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or MariaDB

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

Update `.env` with your MySQL/MariaDB credentials, then initialize the database:

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

For development assets:

```bash
npm run dev
```

## Seeded Accounts

All seeded accounts use password `password`.

- Admin: `admin@elearning.test`
- Admin manager: `gestionnaire@elearning.test`
- Candidate: `yasmine.benali@elearning.test`
- Candidate: `karim.mansouri@elearning.test`
- Trainer: `sami.haddad@elearning.test`
- Trainer: `ines.gharbi@elearning.test`
- Pending trainer: `omar.mejri@elearning.test`

The full seed chain covers all UML models through `DatabaseSeeder`.

## Authentication

The app uses session authentication only:

- `web` guard for `Utilisateur` records.
- `admin` guard for `Administrateur` records.
- Role middleware protects candidate, trainer, and admin areas.
- Pending, rejected, and disabled accounts cannot log in.

No JWT, React, Vue, or separate frontend app is used.

## Tests

```bash
php artisan test
```

Useful focused runs:

```bash
php artisan test --filter AuthenticationAndRolesTest
php artisan test --filter TrainerCourseManagementTest
php artisan test --filter AdminValidationWorkflowTest
```

## Routes

Route groups are documented in [../docs/routes.md](../docs/routes.md).
