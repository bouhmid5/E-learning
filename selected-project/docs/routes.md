# Route Documentation

## Public

- `GET /` - Home page.
- `GET /courses` - Published course catalogue with search, filters, sorting, and pagination.
- `GET /courses/{cours}` - Published course details.
- `GET /categories` - Category list.
- `GET /categories/{categorie}/courses` - Published courses in a category.
- `GET /certificates/verify/{codeVerification}` - Public certificate verification.

## Authentication

- `GET /login` - Login form.
- `POST /login` - Session login for users or admins.
- `POST /logout` - Session logout.
- `GET /register/candidate` - Candidate registration form.
- `POST /register/candidate` - Candidate registration.
- `GET /register/trainer` - Trainer registration form.
- `POST /register/trainer` - Trainer registration, pending admin validation.

## Candidate Area

Middleware: `auth` and `role:candidat`.

- `GET /candidate/dashboard`
- `GET /candidate/enrollments`
- `GET /candidate/enrollments/{inscription}`
- `GET /candidate/enrollments/{inscription}/lessons`
- `POST /candidate/enrollments/{inscription}/lessons/{lecon}/complete`
- `GET /candidate/enrollments/{inscription}/progress`
- `GET /candidate/enrollments/{inscription}/resources/{ressource}/download`
- `GET /candidate/enrollments/{inscription}/certificate/eligibility`
- `POST /candidate/enrollments/{inscription}/certificate`
- `GET /candidate/evaluations/{evaluation}`
- `POST /candidate/evaluations/{evaluation}/start`
- `POST /candidate/evaluations/{evaluation}/submit`
- `GET /candidate/submissions/{soumission}`
- `GET /candidate/results`
- `GET /candidate/certificates`
- `GET /candidate/certificates/{certificat}/download`
- `POST /courses/{cours}/enroll`

## Trainer Area

Middleware: `auth` and `role:formateur`.

- `GET /trainer/dashboard`
- `GET /trainer/courses`
- `GET /trainer/courses/create`
- `POST /trainer/courses`
- `GET /trainer/courses/{cours}`
- `GET /trainer/courses/{cours}/edit`
- `PUT /trainer/courses/{cours}`
- `DELETE /trainer/courses/{cours}`
- `POST /trainer/courses/{cours}/submit`
- `POST /trainer/courses/{cours}/lessons`
- `PUT /trainer/lessons/{lecon}`
- `DELETE /trainer/lessons/{lecon}`
- `POST /trainer/lessons/{lecon}/resources`
- `PUT /trainer/resources/{ressource}`
- `DELETE /trainer/resources/{ressource}`
- `GET /trainer/courses/{cours}/evaluations`
- `GET /trainer/courses/{cours}/evaluations/create`
- `POST /trainer/courses/{cours}/evaluations`
- `GET /trainer/evaluations/{evaluation}/edit`
- `PUT /trainer/evaluations/{evaluation}`
- `DELETE /trainer/evaluations/{evaluation}`
- `POST /trainer/evaluations/{evaluation}/questions`
- `PUT /trainer/questions/{question}`
- `DELETE /trainer/questions/{question}`
- `POST /trainer/questions/{question}/options`
- `PUT /trainer/options/{option}`
- `DELETE /trainer/options/{option}`

## Admin Area

Middleware: `auth:admin` and `role:admin`.

- `GET /admin/dashboard`
- `GET /admin/users`
- `PATCH /admin/users/{user}/status`
- `GET /admin/trainers/pending`
- `POST /admin/trainers/{formateur}/validate`
- `POST /admin/trainers/{formateur}/reject`
- `POST /admin/justificatifs/{justificatif}/validate`
- `POST /admin/justificatifs/{justificatif}/reject`
- `GET /admin/courses/pending`
- `POST /admin/courses/{cours}/validate`
- `POST /admin/courses/{cours}/reject`
- `GET /admin/categories`
- `GET /admin/categories/create`
- `POST /admin/categories`
- `GET /admin/categories/{categorie}/edit`
- `PUT /admin/categories/{categorie}`
- `DELETE /admin/categories/{categorie}`

## Security Notes

- Public course routes expose only published courses.
- Candidate downloads are authorized through policies and support only safe external links or files from the public storage disk.
- Course, trainer, justificatif, certificate, enrollment, and evaluation operations are guarded by middleware, gates, and policies.
- Destructive and workflow-changing Blade forms include confirmation prompts.
