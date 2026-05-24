# E-learning Platform

Laravel modular monolith for the UML-defined e-learning platform.

## Structure

```text
.
|-- AGENTS.md
|-- README.md
|-- IMPLEMENTATION_PLAN.md
|-- docs/
|   `-- conception/
|       |-- class_diagram.puml
|       |-- usecase_diagram.puml
|       |-- business_rules.md
|       |-- ux_ergonomics_rules.md
|       `-- phase_prompts.md
`-- elearning-laravel/
    |-- app/
    |-- database/
    |-- resources/
    |-- routes/
    |-- tests/
    |-- composer.json
    |-- package.json
    `-- .env.example
```

## Domain Scope

The implemented scope follows the UML:

- Candidate and trainer accounts under `Utilisateur`.
- Separate administrator accounts under `Administrateur`.
- Public course catalogue and category browsing.
- Trainer course, lesson, resource, and evaluation management.
- Admin validation of trainers, justificatifs, courses, users, and categories.
- Candidate enrollment, progression, evaluation submission, results, and certificates.

Non-goals remain out of scope unless explicitly requested: payments, livestreaming, SCORM/xAPI, AI correction, microservices, mobile apps, and external analytics.

## Application

The Laravel application is in `elearning-laravel/`. See [elearning-laravel/README.md](elearning-laravel/README.md) for installation, seed data, test commands, and route documentation.
