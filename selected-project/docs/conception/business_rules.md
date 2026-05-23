# Business Rules

## Identity and Roles

- A user must have one or more roles: learner, instructor, or admin.
- Only verified users can enroll, publish courses, or receive certificates.
- Admins can suspend users and unpublish courses.

## Courses

- A course must contain at least one module before it can be published.
- A module must contain at least one lesson before publication.
- Published course slugs must be unique.
- Draft courses are visible only to their instructors and admins.
- Archived courses cannot accept new enrollments.

## Enrollment and Access

- A learner can enroll only once in the same course.
- Paid courses require a confirmed payment before lesson access is granted.
- Free courses grant access immediately after enrollment.
- Suspended learners keep historical records but cannot continue lessons.

## Progress

- Course progress is calculated from completed lessons.
- A course is complete when all required lessons are completed and required quizzes are passed.
- Completion timestamps must not be overwritten once set, except by admin correction.

## Quizzes and Certificates

- Quiz pass score is defined per quiz.
- A quiz attempt is immutable after submission.
- Certificates are issued only after full course completion.
- Certificate serial numbers must be unique and verifiable.

## Payments

- Payment webhook processing must be idempotent.
- Refunds revoke future access unless a business exception is recorded.
- Payment provider references must be stored for reconciliation.

