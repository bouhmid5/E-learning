# Phase Prompts

Use these prompts to guide implementation phases.

## Phase 1 Prompt

Implement the Laravel foundation for the e-learning platform. Configure authentication-ready structure, role-based authorization boundaries, environment examples, coding standards, and baseline tests. Keep the domain model aligned with `docs/conception/class_diagram.puml`.

## Phase 2 Prompt

Implement the core learning workflow: course authoring, modules, lessons, enrollments, lesson progress, learner dashboard, and instructor course management. Add feature tests for enrollment and lesson completion.

## Phase 3 Prompt

Implement evaluation features: quizzes, questions, answers, attempts, scoring, pass thresholds, and certificate generation. Ensure submitted attempts are immutable and certificates are verifiable.

## Phase 4 Prompt

Implement commerce features: paid course access, payment records, payment provider adapter, webhook idempotency, refunds, and access revocation. Keep provider-specific code behind a service boundary.

## Phase 5 Prompt

Implement operational features: admin moderation, reporting, exports, notifications, audit trails, accessibility pass, performance review, and production release checklist.

