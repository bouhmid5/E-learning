# Business Rules — E-learning Platform

## Accounts

- A candidate can register directly.
- A trainer can request registration but starts with EN_ATTENTE status.
- A trainer must upload justificatifs such as diplomas or certificates.
- An admin validates or rejects trainer accounts.
- Disabled, rejected, or inactive accounts cannot log in.

## Roles

- Candidate actions require candidate authentication.
- Trainer actions require trainer authentication.
- Admin actions require admin authentication.
- Admin is separated from the Utilisateur inheritance hierarchy, but login/security must stay centralized.

## Courses

- A trainer creates courses.
- A course starts as BROUILLON.
- A trainer can submit a course for validation.
- A course submitted for validation becomes EN_ATTENTE_VALIDATION.
- An admin validates or rejects submitted courses.
- A rejected course must store motifRejet.
- Only PUBLIE courses are visible in the public/candidate catalogue.
- A course must contain at least one lesson before submission.

## Categories

- A category may have subcategories.
- A category groups courses.
- Admin manages categories and subcategories.

## Lessons and resources

- A course contains one or more lessons.
- A lesson may contain zero or more resources.
- Resources can be VIDEO, DOCUMENT, or LIEN.
- Access to lesson resources requires enrollment, except public preview if explicitly implemented later.

## Enrollment

- Enrollment is represented by the Inscription entity.
- Inscription is the association entity between Candidat and Cours.
- A candidate cannot enroll twice in the same course.
- A candidate can enroll only in PUBLIE courses.
- Enrollment requires authentication.
- Enrollment happens after viewing course details.

## Progression

- Progression is tracked per Inscription.
- Lesson completion is tracked using ProgressionLecon.
- Progression percentage = completed lessons / total lessons.
- When progression reaches 100%, the inscription can become TERMINEE.
- Certification still depends on evaluation success.

## Evaluations

- A course may contain evaluations.
- An evaluation contains one or more questions.
- Question types:
  - QCM
  - VRAI_FAUX
  - REPONSE_COURTE
  - NUMERIQUE

## Automatic correction

- QCM correction uses correct options.
- VRAI_FAUX correction uses the expected boolean answer.
- NUMERIQUE correction uses valeurAttendue and tolerance.
- REPONSE_COURTE correction uses normalized exact matching.
- DEVOIR can use correction criteria.
- Do not invent advanced AI correction.

## Submissions

- A candidate can submit evaluations only for courses where they are enrolled.
- Each submission stores scoreObtenu, reussi, statut, and feedbackAutomatique.
- A submission is successful if scoreObtenu >= seuilReussite.

## Certification

A certificate can be generated only if:

1. The course is completed.
2. Required evaluations are passed.
3. Certification conditions are validated.
4. The inscription is not abandoned.

Certificate generation must be idempotent.

- Do not create duplicate certificates for the same inscription.
- codeVerification must be unique.
- A certificate can be verified by codeVerification.