# E-learning Platform — Codex Instructions

## Project type

This is a Laravel-based e-learning platform developed from UML conception documents.

The project must be implemented phase by phase. Do not generate the full platform at once.

## Source of truth

Before implementing anything, read:

- docs/conception/class_diagram.puml
- docs/conception/usecase_diagram.puml
- docs/conception/business_rules.md
- docs/conception/ux_ergonomics_rules.md
- IMPLEMENTATION_PLAN.md

If there is a conflict between the prompt and the UML, stop and ask for clarification.

## Stack

Use:

- Backend: Laravel
- Frontend: Laravel Blade
- Styling: Blade layouts + Tailwind/Vite if available
- Database: MySQL/MariaDB by default
- Authentication: Laravel session authentication
- Authorization: Laravel middleware, gates, and policies
- Tests: PHPUnit or Pest, depending on the generated Laravel setup

Do not use React.
Do not use Vue unless explicitly requested.
Do not use JWT unless explicitly requested.
Do not split the project into separate backend and frontend apps.

## Architecture

Use a modular Laravel monolith.

Separate clearly:

- Models
- Migrations
- Seeders
- Factories
- Controllers
- Form Requests
- Services
- Policies
- Middleware
- Blade views
- Routes
- Tests

Do not put business logic directly inside controllers.

Use services for domain rules such as:

- course submission validation
- trainer validation
- enrollment rules
- progression calculation
- automatic evaluation correction
- certificate eligibility
- certificate generation

## Core roles

The platform has:

- Candidat
- Formateur
- Administrateur

Candidat and Formateur inherit conceptually from Utilisateur.

Administrateur is separated from the Utilisateur inheritance hierarchy in the UML, but authentication logic must be centralized and not duplicated.

Implementation note:
In Laravel, implement a clean authentication strategy that respects this separation without duplicating password/login code unnecessarily. If needed, use a shared authenticatable base pattern or separate guards, but explain the chosen design before coding.

## Main modules

Implement the project in this order:

1. Project skeleton
2. Domain model and database
3. Authentication and roles
4. Public catalogue
5. Trainer course management
6. Admin validation workflows
7. Enrollment and progression
8. Evaluations and automatic correction
9. Certification
10. Blade frontend screens
11. Final hardening

## Non-goals

Do not implement these unless explicitly requested:

- online payment
- livestreaming
- SCORM/xAPI
- AI correction
- video hosting infrastructure
- microservices
- mobile app
- external analytics
- complex notification infrastructure

## Quality rules

Every backend feature must include tests.

Every protected route must enforce role authorization.

Every destructive action must be protected by authorization and confirmation.

Every phase must end with:

- modified files summary
- tests added
- commands to run
- remaining risks
- next recommended phase

## UX rules

The frontend must respect:

- clarity
- readability
- consistency
- concision
- guidance
- explicit user control
- useful error messages
- confirmation before destructive actions
- predictable navigation
- role-specific dashboards
- no overloaded screens