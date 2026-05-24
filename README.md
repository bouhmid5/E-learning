<<<<<<< HEAD
# 🎓 EduNova — E-Learning Platform

> **Futuristic · Premium · Production-Ready**  
> A full-stack e-learning platform with Angular frontend, NestJS backend, and MySQL database.

---

## 📸 Platform Overview

EduNova is a modern, enterprise-grade e-learning platform featuring:

- 🎨 **Premium glassmorphism UI** with dark/light mode, animations, micro-interactions
- 🧑‍💻 **Candidate space** — Browse courses, learn, take quizzes, earn certificates
- 👩‍🏫 **Trainer space** — Create courses, manage students, view analytics
- 🔒 **Admin space** — Validate trainers/courses, platform analytics, support management
- 🏆 **Gamification** — XP system, badges, streaks, leaderboards
- 📜 **Certificate system** — Auto-generated PDFs with QR verification
- 🤖 **AI Recommendations** — Personalized course suggestions
- 📊 **Analytics dashboards** — Real-time stats for all roles

---

## 🗂 Project Structure

```
elearning-platform/
├── frontend/                     # Static HTML/CSS/JS frontend
│   ├── index.html                # Landing page
│   ├── styles/
│   │   └── main.css              # Global styles, design tokens, components
│   ├── js/
│   │   └── main.js               # All interactions, charts, animations
│   └── pages/
│       ├── catalogue.html        # Course catalogue with filters
│       ├── cours-detail.html     # Course detail & enrollment
│       ├── login.html            # Authentication
│       ├── register.html         # Multi-step registration
│       ├── candidat/
│       │   ├── dashboard.html    # Learner dashboard
│       │   ├── lecon.html        # Video lesson player
│       │   ├── quiz.html         # Interactive quiz system
│       │   ├── certificats.html  # Certificate management
│       │   └── mes-cours.html    # My courses
│       ├── formateur/
│       │   ├── dashboard.html    # Trainer dashboard
│       │   ├── creer-cours.html  # Course builder
│       │   └── etudiants.html    # Student management
│       └── admin/
│           └── dashboard.html    # Admin control panel
│
├── backend/                      # NestJS REST API
│   └── src/
│       ├── app.module.ts         # Root module
│       ├── main.ts               # Bootstrap with Swagger
│       ├── config/               # Database, JWT, App configs
│       ├── common/
│       │   ├── decorators/       # @Public, @Roles, @CurrentUser
│       │   ├── guards/           # JwtAuthGuard, RolesGuard
│       │   ├── interceptors/     # Transform, Logging
│       │   ├── filters/          # Global exception filter
│       │   ├── pipes/            # ParseUUID
│       │   └── enums/            # Shared enumerations
│       └── modules/
│           ├── auth/             # JWT auth, registration, password reset
│           ├── utilisateurs/     # User, Candidat, Formateur entities
│           ├── cours/            # Course CRUD + approval workflow
│           ├── categories/       # Course categories
│           ├── inscriptions/     # Enrollment + lesson progress tracking
│           ├── evaluations/      # Quizzes, exams, auto-correction
│           ├── certificats/      # PDF cert generation + QR verification
│           ├── notifications/    # In-app notification system
│           ├── messages/         # Candidate ↔ Trainer messaging
│           ├── badges/           # Gamification: XP, badges, streaks
│           ├── support/          # Support ticket system
│           ├── upload/           # Secure file upload (video, PDF, image)
│           └── admin/            # Admin-only management endpoints
│
└── database/
    └── schema.sql                # Full MySQL schema with indexes, seeds, views
```

---

## 🚀 Quick Start

### Prerequisites
- Node.js 20+
- MySQL 8.0+
- Git

---

### 1. Frontend (Static Files)

The frontend is built with vanilla HTML, CSS, and JavaScript — no build step required.

```bash
# Option A: Open directly in browser
open frontend/index.html

# Option B: Serve with any static server
npx serve frontend/
# → Available at http://localhost:3000

# Option C: VS Code Live Server
# Right-click index.html → Open with Live Server
```

**Demo accounts (frontend simulation):**
| Role      | Redirect after login         |
|-----------|------------------------------|
| Candidate | `/pages/candidat/dashboard.html` |
| Trainer   | `/pages/formateur/dashboard.html` |
| Admin     | `/pages/admin/dashboard.html` |

---

### 2. Database Setup

```bash
# Create database and import schema
mysql -u root -p < database/schema.sql

# Or via phpMyAdmin:
# 1. Create database "edunova_db" (utf8mb4_unicode_ci)
# 2. Import database/schema.sql
```

---

### 3. Backend (NestJS API)

```bash
cd backend

# Install dependencies
npm install

# Environment configuration
cp .env.example .env
# Edit .env with your database credentials
```

**`.env` file:**
```env
# App
NODE_ENV=development
PORT=3000
CORS_ORIGINS=http://localhost:4200,http://localhost:3001

# Database
DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=your_password
DB_NAME=edunova_db

# JWT
JWT_SECRET=your-super-secret-jwt-key-2026
JWT_EXPIRES_IN=15m
JWT_REFRESH_SECRET=your-refresh-secret-2026
JWT_REFRESH_EXPIRES=7d

# Email (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=your@email.com
MAIL_PASS=your_app_password
MAIL_FROM="EduNova <noreply@edunova.com>"

# File Storage
UPLOAD_PATH=./uploads
MAX_FILE_SIZE=104857600
CDN_URL=http://localhost:3000/uploads

# Optional: AWS S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_REGION=eu-west-1
AWS_BUCKET_NAME=edunova-uploads
```

```bash
# Run in development mode
npm run start:dev

# Build for production
npm run build
npm run start:prod
```

**API available at:** `http://localhost:3000/api/v1`  
**Swagger docs:** `http://localhost:3000/api/docs`

---

## 📡 API Reference

### Authentication
| Method | Endpoint                    | Auth | Description                  |
|--------|----------------------------|------|------------------------------|
| POST   | `/api/v1/auth/register`    | ❌   | Register new user             |
| POST   | `/api/v1/auth/login`       | ❌   | Login → returns JWT tokens    |
| POST   | `/api/v1/auth/refresh`     | ❌   | Refresh access token          |
| GET    | `/api/v1/auth/verify-email`| ❌   | Verify email address          |
| POST   | `/api/v1/auth/forgot-password` | ❌ | Request password reset       |
| POST   | `/api/v1/auth/reset-password` | ❌  | Reset password with token    |
| POST   | `/api/v1/auth/logout`      | ✅   | Logout + invalidate tokens    |
| GET    | `/api/v1/auth/me`          | ✅   | Get current user profile      |

### Courses
| Method | Endpoint                            | Auth        | Description                    |
|--------|-------------------------------------|-------------|--------------------------------|
| GET    | `/api/v1/cours`                     | ❌          | List all published courses      |
| GET    | `/api/v1/cours/:slug`               | ❌          | Get course details              |
| POST   | `/api/v1/cours`                     | FORMATEUR   | Create new course               |
| PUT    | `/api/v1/cours/:id`                 | FORMATEUR   | Update course                   |
| PATCH  | `/api/v1/cours/:id/soumettre`       | FORMATEUR   | Submit for admin review         |
| PATCH  | `/api/v1/cours/:id/approuver`       | ADMIN       | Approve & publish course        |
| PATCH  | `/api/v1/cours/:id/rejeter`         | ADMIN       | Reject course with reason       |
| GET    | `/api/v1/cours/formateur/mes-cours` | FORMATEUR   | Get trainer's own courses       |

### Enrollments
| Method | Endpoint                                          | Auth      | Description              |
|--------|---------------------------------------------------|-----------|--------------------------|
| POST   | `/api/v1/inscriptions/:coursId`                  | CANDIDAT  | Enroll in a course       |
| DELETE | `/api/v1/inscriptions/:coursId`                  | CANDIDAT  | Unenroll from course     |
| GET    | `/api/v1/inscriptions/mes-cours`                 | CANDIDAT  | My enrollments           |
| PATCH  | `/api/v1/inscriptions/:id/lecon/:leconId/complete` | CANDIDAT | Mark lesson complete     |

### Evaluations
| Method | Endpoint                                    | Auth      | Description              |
|--------|--------------------------------------------|-----------|--------------------------|
| GET    | `/api/v1/evaluations/cours/:coursId`       | CANDIDAT  | Get course quizzes       |
| GET    | `/api/v1/evaluations/:id/commencer`        | CANDIDAT  | Start a quiz session     |
| POST   | `/api/v1/evaluations/soumettre/:id`        | CANDIDAT  | Submit quiz answers      |
| GET    | `/api/v1/evaluations/mes-resultats`        | CANDIDAT  | Get quiz history         |

### Certificates
| Method | Endpoint                              | Auth      | Description                |
|--------|--------------------------------------|-----------|----------------------------|
| POST   | `/api/v1/certificats/generer/:inscId`| CANDIDAT  | Generate certificate       |
| GET    | `/api/v1/certificats/mes-certificats`| CANDIDAT  | List my certificates       |
| GET    | `/api/v1/certificats/verifier/:code` | ❌        | Publicly verify certificate|
| GET    | `/api/v1/certificats/telecharger/:code`| CANDIDAT | Get PDF download URL     |

---

## 🗃 Database Schema Summary

| Table                  | Description                                     |
|------------------------|-------------------------------------------------|
| `utilisateur`          | Base user (candidat or formateur)               |
| `candidat`             | Learner profile (extends utilisateur)           |
| `formateur`            | Trainer profile (extends utilisateur)           |
| `administrateur`       | **Independent** admin entity (no utilisateur FK) |
| `categorie`            | Course categories (supports nesting)            |
| `cours`                | Course with full lifecycle management           |
| `section`              | Course sections/chapters                        |
| `lecon`                | Individual lessons (video, quiz, document)      |
| `ressource`            | Downloadable resources per lesson               |
| `inscription`          | **Association class** Candidat ↔ Cours          |
| `progression_lecon`    | Per-lesson completion tracking                  |
| `evaluation`           | Quiz or exam definition                         |
| `question_quiz`        | Quiz questions with options                     |
| `soumission_evaluation`| Quiz submission & auto-correction               |
| `devoir`               | Assignment submission with file upload          |
| `certificat`           | Verified certificate with QR code + PDF         |
| `avis`                 | Course reviews and ratings                      |
| `notification`         | In-app notifications                            |
| `message`              | Direct messaging system                         |
| `support_ticket`       | Customer support tickets                        |
| `badge`                | Gamification badge definitions                  |
| `utilisateur_badge`    | Earned badges per user                          |

---

## 🎨 UI Design System

### Color Palette
```css
--bg-void:        #080c14   /* Deepest background */
--bg-deep:        #0d1220   /* Page background */
--bg-surface:     #111827   /* Card surface */
--bg-elevated:    #1a2235   /* Elevated elements */
--accent-cyan:    #00e5ff   /* Primary accent */
--accent-violet:  #7c3aed   /* Secondary accent */
--accent-emerald: #10b981   /* Success */
--accent-amber:   #f59e0b   /* Warning / XP */
--accent-rose:    #f43f5e   /* Error / Hot */
```

### Typography
- **Display:** Syne (headings, numbers, bold UI)
- **Body:** DM Sans (all body text, descriptions)

### Key Components
- **Glass cards** — `rgba(255,255,255,0.04)` + backdrop blur
- **Animated progress bars** — shimmer effect
- **Floating hero cards** — CSS keyframe animations
- **Smooth page transitions** — 300ms cubic-bezier
- **Canvas charts** — Vanilla JS line, bar, donut charts
- **Toast notifications** — Slide-in with auto-dismiss
- **Skeleton loading** — Gradient pulse animation

---

## 🔐 Security Features

| Feature                  | Implementation                          |
|--------------------------|----------------------------------------|
| Password hashing         | bcrypt (salt rounds: 12)               |
| JWT access tokens        | 15 minute expiry                        |
| JWT refresh tokens       | 7 day expiry, stored in DB             |
| Email verification       | UUID token sent on registration        |
| Password reset           | Time-limited UUID token (1 hour)       |
| Rate limiting            | @nestjs/throttler (100 req/min global) |
| CORS                     | Whitelist-based origin validation      |
| Helmet                   | Security HTTP headers                  |
| Input validation         | class-validator on all DTOs            |
| SQL injection protection | TypeORM parameterized queries          |
| File upload safety       | MIME type + extension validation       |
| Role-based access        | Guards on every protected endpoint     |

---

## 🐳 Docker Support

```bash
# docker-compose.yml (create in root)
version: '3.8'
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: edunova_db
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./database/schema.sql:/docker-entrypoint-initdb.d/schema.sql

  api:
    build: ./backend
    ports:
      - "3000:3000"
    environment:
      DB_HOST: db
      DB_PORT: 3306
      DB_USERNAME: root
      DB_PASSWORD: rootpass
      DB_NAME: edunova_db
    depends_on:
      - db

volumes:
  mysql_data:
```

```bash
docker-compose up -d
```

---

## 📦 NPM Dependencies (Backend)

```json
{
  "dependencies": {
    "@nestjs/common": "^10.0.0",
    "@nestjs/core": "^10.0.0",
    "@nestjs/platform-express": "^10.0.0",
    "@nestjs/config": "^3.0.0",
    "@nestjs/jwt": "^10.0.0",
    "@nestjs/passport": "^10.0.0",
    "@nestjs/swagger": "^7.0.0",
    "@nestjs/throttler": "^5.0.0",
    "@nestjs/typeorm": "^10.0.0",
    "typeorm": "^0.3.0",
    "mysql2": "^3.0.0",
    "passport": "^0.6.0",
    "passport-jwt": "^4.0.0",
    "bcrypt": "^5.1.0",
    "class-validator": "^0.14.0",
    "class-transformer": "^0.5.0",
    "helmet": "^7.0.0",
    "compression": "^1.7.4",
    "slugify": "^1.6.0",
    "uuid": "^9.0.0",
    "qrcode": "^1.5.0",
    "pdfkit": "^0.14.0",
    "multer": "^1.4.5",
    "sharp": "^0.32.0",
    "nodemailer": "^6.9.0"
  }
}
```

---

## 🌐 Pages Navigation Map

```
/ (index.html)
├── /pages/catalogue.html          — Course catalogue + filters
├── /pages/cours-detail.html       — Course detail + enrollment
├── /pages/login.html              — Sign in (candidate / trainer)
├── /pages/register.html           — 3-step registration
│
├── /pages/candidat/
│   ├── dashboard.html             — Learner home + stats + streaks
│   ├── lecon.html                 — Video player + lesson nav
│   ├── quiz.html                  — Interactive quiz with timer
│   ├── certificats.html           — Earned certificates
│   └── mes-cours.html             — All enrolled courses
│
├── /pages/formateur/
│   ├── dashboard.html             — Trainer analytics + revenue
│   ├── creer-cours.html           — Course builder wizard
│   └── etudiants.html             — Student management table
│
└── /pages/admin/
    └── dashboard.html             — Platform control center
```

---

## 🏆 Gamification System

| Action                     | XP Earned |
|----------------------------|-----------|
| Complete a lesson          | +10 XP    |
| Pass a quiz (70%+)         | +100 XP   |
| Pass a quiz (90%+)         | +150 XP   |
| Complete a course          | +300 XP   |
| Earn a certificate         | +200 XP   |
| 7-day streak               | +100 XP   |
| 30-day streak              | +500 XP   |

| Level        | XP Required |
|--------------|-------------|
| 🌱 Beginner  | 0 – 999     |
| ⚡ Intermediate | 1,000 – 4,999 |
| 🔥 Advanced  | 5,000 – 14,999 |
| 💎 Expert    | 15,000+     |

---

## 👥 UML Architecture Notes

### Class Diagram (Key Relations)
- `Candidat` **inherits** → `Utilisateur`
- `Formateur` **inherits** → `Utilisateur`  
- `Administrateur` is **INDEPENDENT** (no Utilisateur link)
- `Inscription` is an **Association Class** between `Candidat` and `Cours`
- `Visiteur` actor was **removed** — all public actions transferred to `Candidat`

---

## 🤝 License & Credits

Built for the **EduNova E-Learning Platform** project.  
Designed with ❤️ using glassmorphism, Syne + DM Sans, and a futuristic dark palette.

**Stack:** Vanilla HTML/CSS/JS · NestJS · TypeORM · MySQL · JWT · bcrypt · PDFKit · QRCode

---

*EduNova Platform v1.0 — © 2026*
=======
# E-learning
E-learning Project
\# E-Learning Platform



Projet IHM - plateforme e-learning



Structure :

\- candidates/

\- assets/

\- docs/

\- templates/



>>>>>>> 495c299832f192e707caaec23eac70f6a4e01c1f
