# Codex Phase Prompts — Laravel Version

## Global rule

Before starting any phase, read:

- AGENTS.md
- IMPLEMENTATION_PLAN.md
- docs/conception/class_diagram.puml
- docs/conception/usecase_diagram.puml
- docs/conception/business_rules.md
- docs/conception/ux_ergonomics_rules.md

Work only on the requested phase.
Do not implement future phases early.
Stop after the phase and summarize:

- modified files
- tests added
- commands to run
- remaining risks
- next step

---

## Phase 0 Prompt — Laravel project skeleton

Inspect the repository.

If a Laravel project already exists:
- analyze the structure
- identify routes, controllers, models, migrations, views, auth setup, database config, and test setup
- do not rewrite everything
- produce an implementation plan aligned with the UML e-learning platform

If the repository is empty:
- create a Laravel application inside elearning-laravel/
- configure MySQL/MariaDB in .env.example
- add a basic README.md
- add basic Blade layout structure
- add Vite/Tailwind only if the Laravel starter supports it cleanly
- do not implement business features yet

Expected structure:
- app/Models
- app/Http/Controllers
- app/Http/Requests
- app/Services
- app/Policies
- app/Enums or app/Constants
- database/migrations
- database/factories
- database/seeders
- resources/views
- routes/web.php
- tests/Feature
- tests/Unit

Stop after this phase.

---

## Phase 1 Prompt — Domain model and database

Implement the Laravel backend domain model and database schema.

Use the UML in docs/conception/class_diagram.puml as source of truth.

Implement Eloquent models and migrations for:

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

Implement statuses/constants/enums for:

- StatutCompte: EN_ATTENTE, ACTIF, DESACTIVE, REJETE
- StatutCours: BROUILLON, EN_ATTENTE_VALIDATION, PUBLIE, REJETE, ARCHIVE
- StatutInscription: EN_COURS, TERMINEE, ABANDONNEE
- StatutSoumission: SOUMISE, CORRIGEE, REUSSIE, ECHOUEE
- StatutJustificatif: EN_ATTENTE, VALIDE, REJETE
- TypeEvaluation: QUIZ, EXAMEN, DEVOIR
- TypeRessource: VIDEO, DOCUMENT, LIEN
- TypeQuestion: QCM, VRAI_FAUX, REPONSE_COURTE, NUMERIQUE

Rules:
- Inscription is an association entity between Candidat and Cours.
- Add unique constraint on candidat_id + cours_id.
- Add unique constraint on certificats.code_verification.
- Add unique constraint on progression_lecons by inscription_id + lecon_id.
- Categorie supports self-referencing parent/children relation.
- Cours belongs to Categorie.
- Cours belongs to Formateur.
- Cours has many Lecon.
- Lecon has many Ressource.
- Cours has many Evaluation.
- Evaluation has many Question.
- Question has many OptionReponse.
- Evaluation has many CritereCorrection.
- SoumissionEvaluation has many ReponseCandidat.
- Certificat belongs to Inscription.

Important:
- Avoid dangerous cascade deletes on users/candidates if submissions or certificates exist.
- Prefer restricted deletes or soft deletes where appropriate.
- Use Laravel timestamps.
- Use clear foreign key names.
- Add factories where useful.
- Add basic seeders for development data.

Add tests for:
- model creation
- key relationships
- unique candidate/course enrollment
- unique certificate code
- unique lesson progression per inscription and lesson

Stop after this phase.

---

## Phase 2 Prompt — Authentication and roles

Implement Laravel authentication and role-based authorization.

Requirements:
- Use Laravel session authentication, not JWT.
- Implement login, logout, register candidate, register trainer.
- Trainer registration starts with EN_ATTENTE status.
- Admin login must be supported.
- Disabled/rejected/inactive accounts cannot log in.
- Candidate, trainer, and admin areas must be protected.
- Use middleware and policies/gates.
- Do not duplicate authentication logic unnecessarily.

Clarify and implement the chosen auth structure:
Option A:
- one authenticatable table with role and profile tables

Option B:
- separate Utilisateur and Administrateur authenticatable models with guards

Choose the cleanest option that respects the UML and explain it before coding.

Routes/pages:
- GET /login
- POST /login
- POST /logout
- GET /register/candidate
- POST /register/candidate
- GET /register/trainer
- POST /register/trainer
- GET /dashboard
- GET /candidate/dashboard
- GET /trainer/dashboard
- GET /admin/dashboard

Add middleware:
- auth
- role:candidat
- role:formateur
- role:admin

Add tests for:
- candidate registration
- trainer registration pending status
- successful login
- rejected account cannot login
- disabled account cannot login
- protected route requires authentication
- role-based route protection

Stop after this phase.

---

## Phase 3 Prompt — Public catalogue

Implement public course catalogue browsing in Laravel.

Requirements:
- List only PUBLIE courses.
- Search courses by title and description.
- Simple filtering:
  - category
  - level/niveau
  - language/langue
- Advanced filtering:
  - price range
  - duration
  - trainer
  - keyword
- View course details.
- Enrollment must not be implemented yet.
- Pagination required.
- Sorting supported by:
  - date_publication
  - titre
  - prix
  - duree_estimee
- Clear empty state in Blade view.
- Do not expose draft/rejected/archived courses to candidates/public users.

Routes:
- GET /courses
- GET /courses/{cours}
- GET /categories
- GET /categories/{categorie}/courses

Laravel structure:
- CourseCatalogueController
- CategoryController
- CourseFilterRequest if needed
- Blade views:
  - resources/views/courses/index.blade.php
  - resources/views/courses/show.blade.php
  - resources/views/categories/index.blade.php

Add tests for:
- only published courses visible
- search works
- filters work
- sorting works
- unpublished course details are not accessible publicly

Stop after this phase.

---

## Phase 4 Prompt — Trainer course management

Implement trainer course management.

Requirements:
- Trainer can create a course as BROUILLON.
- Trainer can update own BROUILLON or REJETE courses.
- Trainer can add/update/delete lessons.
- Trainer can add/update/delete resources.
- Trainer can submit course for validation.
- A course cannot be submitted if it has no lessons.
- A trainer cannot edit another trainer's course.
- Submitted courses become EN_ATTENTE_VALIDATION.
- Rejected courses store motif_rejet.
- File resources must use Laravel Storage.

Routes:
- GET /trainer/courses
- GET /trainer/courses/create
- POST /trainer/courses
- GET /trainer/courses/{cours}
- GET /trainer/courses/{cours}/edit
- PUT /trainer/courses/{cours}
- DELETE /trainer/courses/{cours}
- POST /trainer/courses/{cours}/lessons
- PUT /trainer/lessons/{lecon}
- DELETE /trainer/lessons/{lecon}
- POST /trainer/lessons/{lecon}/resources
- PUT /trainer/resources/{ressource}
- DELETE /trainer/resources/{ressource}
- POST /trainer/courses/{cours}/submit

Laravel structure:
- Trainer/CourseController
- Trainer/LessonController
- Trainer/ResourceController
- CourseWorkflowService
- CoursePolicy
- Form Requests for validation
- Blade views under resources/views/trainer/courses

Add tests for:
- trainer can create own course
- trainer cannot edit another trainer's course
- course without lesson cannot be submitted
- submitted course becomes EN_ATTENTE_VALIDATION
- rejected course can be edited
- published course cannot be edited directly by trainer unless explicitly allowed

Stop after this phase.

---

## Phase 5 Prompt — Admin validation workflows

Implement admin validation workflows.

Requirements:
- Admin can list users.
- Admin can activate/deactivate users.
- Admin can validate/reject trainer accounts.
- Admin can validate/reject trainer justificatifs.
- Admin can validate/reject courses.
- Admin can manage categories and subcategories.
- Rejection requires a reason.
- Admin-only access must be enforced.

Routes:
- GET /admin/users
- PATCH /admin/users/{user}/status
- GET /admin/trainers/pending
- POST /admin/trainers/{formateur}/validate
- POST /admin/trainers/{formateur}/reject
- GET /admin/courses/pending
- POST /admin/courses/{cours}/validate
- POST /admin/courses/{cours}/reject
- GET /admin/categories
- GET /admin/categories/create
- POST /admin/categories
- GET /admin/categories/{categorie}/edit
- PUT /admin/categories/{categorie}
- DELETE /admin/categories/{categorie}

Laravel structure:
- Admin/UserController
- Admin/TrainerValidationController
- Admin/CourseValidationController
- Admin/CategoryController
- AdminValidationService
- Policies/middleware
- Blade views under resources/views/admin

Add tests for:
- admin-only access
- trainer validation
- trainer rejection with reason
- course validation
- course rejection with motif_rejet
- category CRUD

Stop after this phase.

---

## Phase 6 Prompt — Candidate enrollment and progression

Implement candidate enrollment and progression.

Requirements:
- Candidate can enroll only in PUBLIE courses.
- Candidate cannot enroll twice in the same course.
- Candidate can list current courses.
- Candidate can access lessons/resources only for enrolled courses.
- Candidate can mark a lesson as completed.
- Progression is calculated as completed lessons / total lessons.
- When progression reaches 100%, inscription can become TERMINEE.
- Downloading supports must respect enrollment access.

Routes:
- POST /courses/{cours}/enroll
- GET /candidate/enrollments
- GET /candidate/enrollments/{inscription}
- GET /candidate/enrollments/{inscription}/lessons
- POST /candidate/enrollments/{inscription}/lessons/{lecon}/complete
- GET /candidate/enrollments/{inscription}/progress

Laravel structure:
- Candidate/EnrollmentController
- Candidate/ProgressionController
- EnrollmentService
- ProgressionService
- InscriptionPolicy
- Blade views under resources/views/candidate

Add tests for:
- enrollment requires candidate auth
- enrollment only for PUBLIE courses
- duplicate enrollment blocked
- lesson access blocked without enrollment
- marking lesson complete creates/updates ProgressionLecon
- progression calculation works
- 100% progression marks inscription TERMINEE

Stop after this phase.

---

## Phase 7 Prompt — Evaluations and automatic correction

Implement evaluations and automatic correction.

Trainer side:
- Trainer can create evaluations for own courses.
- Trainer can add questions.
- Trainer can add options for QCM/VRAI_FAUX.
- Trainer can define seuil_reussite.
- Trainer can define correction criteria for devoirs.
- Trainer cannot modify evaluations of another trainer's course.

Candidate side:
- Candidate can start and submit an evaluation only if enrolled in the course.
- Candidate answers are stored as ReponseCandidat.
- Auto-correction supports:
  - QCM
  - VRAI_FAUX
  - NUMERIQUE with tolerance
  - REPONSE_COURTE with normalized exact matching
- Score is calculated automatically.
- reussi is true when score_obtenu >= seuil_reussite.
- Store feedback_automatique.

Routes:
Trainer:
- GET /trainer/courses/{cours}/evaluations
- GET /trainer/courses/{cours}/evaluations/create
- POST /trainer/courses/{cours}/evaluations
- GET /trainer/evaluations/{evaluation}/edit
- PUT /trainer/evaluations/{evaluation}
- DELETE /trainer/evaluations/{evaluation}
- POST /trainer/evaluations/{evaluation}/questions
- PUT /trainer/questions/{question}
- DELETE /trainer/questions/{question}
- POST /trainer/questions/{question}/options
- PUT /trainer/options/{option}
- DELETE /trainer/options/{option}

Candidate:
- GET /candidate/evaluations/{evaluation}
- POST /candidate/evaluations/{evaluation}/start
- POST /candidate/evaluations/{evaluation}/submit
- GET /candidate/submissions/{soumission}
- GET /candidate/results

Laravel structure:
- Trainer/EvaluationController
- Trainer/QuestionController
- Trainer/OptionReponseController
- Candidate/EvaluationSubmissionController
- AutomaticCorrectionService
- EvaluationPolicy
- Blade views for trainer and candidate evaluations

Add tests for:
- trainer owns evaluation course
- candidate must be enrolled to submit
- QCM scoring
- VRAI_FAUX scoring
- NUMERIQUE tolerance scoring
- REPONSE_COURTE normalized scoring
- pass/fail threshold

Stop after this phase.

---

## Phase 8 Prompt — Certification

Implement certification eligibility and certificate generation.

Eligibility:
- progression = 100%
- required evaluations passed
- inscription not abandoned
- certification conditions validated

Requirements:
- Candidate can request certificate generation.
- Certificate generation is idempotent.
- Generate unique code_verification.
- Store fichier_url or generated certificate path.
- Certificate can be verified by code_verification.
- Candidate can download active certificate.
- Do not create duplicate certificates for the same inscription.

Routes:
- GET /candidate/enrollments/{inscription}/certificate/eligibility
- POST /candidate/enrollments/{inscription}/certificate
- GET /candidate/certificates
- GET /certificates/verify/{codeVerification}
- GET /candidate/certificates/{certificat}/download

Laravel structure:
- Candidate/CertificateController
- PublicCertificateVerificationController
- CertificateEligibilityService
- CertificateGenerationService
- CertificatPolicy
- Blade views:
  - candidate certificates page
  - certificate verification page

Add tests for:
- not eligible before completion
- not eligible before passing evaluations
- certificate generated once
- duplicate certificate blocked/idempotent
- verification by code works
- only owner can download certificate

Stop after this phase.

---

## Phase 9 Prompt — Blade frontend screens

Implement Laravel Blade frontend screens.

Use:
- Blade templates
- Laravel layouts
- reusable components/partials
- Tailwind/Vite if available
- role-based navigation
- clear validation messages
- confirmation dialogs
- loading/disabled states where useful
- empty states

Candidate pages:
- login/register
- catalogue
- course details
- my courses
- course learning page
- progress page
- evaluation page
- results page
- certificate page

Trainer pages:
- dashboard
- my courses
- course editor
- lesson/resource editor
- evaluation editor
- candidate performance

Admin pages:
- dashboard
- user management
- trainer validation
- course validation
- category management
- statistics
- support/reclamations

Start only with:
- shared layout
- navigation
- authentication pages
- catalogue page
- course details page
- role dashboards

Do not attempt to polish every page at once.
Stop after this first frontend slice.

---

## Phase 10 Prompt — Final hardening

Final hardening.

Tasks:
- review all business rules
- add missing tests
- add seed data
- add README installation instructions
- add route documentation
- check security
- check role guards
- check policies
- check file upload restrictions
- check validation messages
- check UX consistency
- check error handling
- check empty states
- check destructive action confirmations

Do not add new features outside the UML scope.

Stop after this phase.