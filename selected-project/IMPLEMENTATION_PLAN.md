# Implementation Plan

## Phase 0 — Repository audit and Laravel skeleton

Goal:
Set up or inspect the Laravel project.

Output:
- Laravel application folder
- MySQL/MariaDB configuration
- README
- .env.example
- basic Blade layout
- database connection notes

Do not implement business features yet.

## Phase 1 — Domain model and database

Goal:
Implement Eloquent models, enums/constants, migrations, relationships, factories, and basic seeders.

Core entities:
- Utilisateur
- Candidat
- Formateur
- Administrateur
- JustificatifFormateur
- Categorie
- Cours
- Lecon
- Ressource
- Inscription
- ProgressionLecon
- Evaluation
- Question
- OptionReponse
- CritereCorrection
- SoumissionEvaluation
- ReponseCandidat
- Certificat

Critical constraints:
- unique candidate/course enrollment
- unique certificate verification code
- unique lesson progression per inscription and lesson

## Phase 2 — Authentication and authorization

Goal:
Implement Laravel session auth, role-based middleware, policies, registration, login, logout, and protected dashboards.

Roles:
- CANDIDAT
- FORMATEUR
- ADMIN

## Phase 3 — Public catalogue

Goal:
List, search, filter, and view published courses.

## Phase 4 — Trainer course management

Goal:
Allow trainers to create courses, lessons, resources, evaluations, and submit courses for validation.

## Phase 5 — Admin validation

Goal:
Allow admins to validate/reject trainers and courses, manage users, and manage categories.

## Phase 6 — Enrollment and progression

Goal:
Allow candidates to enroll, access enrolled courses, complete lessons, and track progression.

## Phase 7 — Evaluations and automatic correction

Goal:
Implement quiz/exam/devoir submission and correction.

## Phase 8 — Certification

Goal:
Verify eligibility and generate certificates.

## Phase 9 — Blade frontend screens

Goal:
Implement role-based Laravel Blade screens connected to backend logic.

## Phase 10 — Final hardening

Goal:
Add missing tests, docs, seed data, error handling, security checks, and UX refinements.