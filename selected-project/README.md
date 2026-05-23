# E-learning Platform

Architecture and implementation scaffold for a Laravel-based e-learning platform.

## Structure

```text
.
├── AGENTS.md
├── README.md
├── IMPLEMENTATION_PLAN.md
├── docs/
│   └── conception/
│       ├── class_diagram.puml
│       ├── usecase_diagram.puml
│       ├── business_rules.md
│       ├── ux_ergonomics_rules.md
│       └── phase_prompts.md
└── elearning-laravel/
    ├── app/
    ├── database/
    ├── resources/
    ├── routes/
    ├── tests/
    ├── composer.json
    ├── package.json
    └── .env.example
```

## Getting Started

The Laravel folder is a lightweight scaffold. Install dependencies and expand the framework files when implementation begins:

```bash
cd elearning-laravel
composer install
npm install
cp .env.example .env
```

## Domain Scope

The platform targets:

- Learner enrollment and course progression.
- Instructor course authoring.
- Lessons, quizzes, attempts, certificates, and payments.
- Admin moderation, reporting, and user management.

