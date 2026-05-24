-- ============================================================
-- EduNova E-Learning Platform — MySQL Database Schema
-- Compatible with phpMyAdmin / MySQL 8.0+
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `edunova_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `edunova_db`;

-- ─────────────────────────────────────────────────────────────
-- TABLE: utilisateur
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `prenom`          VARCHAR(100)  NOT NULL,
  `nom`             VARCHAR(100)  NOT NULL,
  `email`           VARCHAR(255)  NOT NULL UNIQUE,
  `motDePasse`      VARCHAR(255)  NOT NULL,
  `role`            ENUM('CANDIDAT','FORMATEUR') NOT NULL,
  `avatar`          VARCHAR(500)  DEFAULT NULL,
  `bio`             TEXT          DEFAULT NULL,
  `telephone`       VARCHAR(20)   DEFAULT NULL,
  `dateNaissance`   DATE          DEFAULT NULL,
  `langue`          VARCHAR(10)   NOT NULL DEFAULT 'fr',
  `niveauExperience` ENUM('DEBUTANT','INTERMEDIAIRE','AVANCE') DEFAULT 'DEBUTANT',
  `emailVerifie`    TINYINT(1)    NOT NULL DEFAULT 0,
  `actif`           TINYINT(1)    NOT NULL DEFAULT 1,
  `tokenVerif`      VARCHAR(255)  DEFAULT NULL,
  `tokenReset`      VARCHAR(255)  DEFAULT NULL,
  `tokenResetExpiry` DATETIME     DEFAULT NULL,
  `refreshToken`    VARCHAR(500)  DEFAULT NULL,
  `dernierConnexion` DATETIME     DEFAULT NULL,
  `xp`              INT UNSIGNED  NOT NULL DEFAULT 0,
  `niveau`          VARCHAR(50)   NOT NULL DEFAULT 'Beginner',
  `streakJours`     INT UNSIGNED  NOT NULL DEFAULT 0,
  `derniereActivite` DATE         DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email`  (`email`),
  INDEX `idx_role`   (`role`),
  INDEX `idx_actif`  (`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: candidat (extends utilisateur)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `candidat` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `utilisateurId`   CHAR(36)      NOT NULL UNIQUE,
  `objectifApprentissage` VARCHAR(255) DEFAULT NULL,
  `domainesInteret` JSON          DEFAULT NULL,
  `posteActuel`     VARCHAR(200)  DEFAULT NULL,
  `linkedin`        VARCHAR(500)  DEFAULT NULL,
  `github`          VARCHAR(500)  DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`utilisateurId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: formateur (extends utilisateur)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `formateur` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `utilisateurId`   CHAR(36)      NOT NULL UNIQUE,
  `specialite`      VARCHAR(200)  DEFAULT NULL,
  `anneesExperience` INT          DEFAULT 0,
  `linkedin`        VARCHAR(500)  DEFAULT NULL,
  `siteWeb`         VARCHAR(500)  DEFAULT NULL,
  `cv`              VARCHAR(500)  DEFAULT NULL,
  `statut`          ENUM('EN_ATTENTE','VALIDE','REJETE','SUSPENDU') NOT NULL DEFAULT 'EN_ATTENTE',
  `noteGlobale`     DECIMAL(3,2)  DEFAULT 0.00,
  `totalEtudiants`  INT UNSIGNED  DEFAULT 0,
  `totalCours`      INT UNSIGNED  DEFAULT 0,
  `revenus`         DECIMAL(12,2) DEFAULT 0.00,
  `raisonRejet`     TEXT          DEFAULT NULL,
  `valideParId`     CHAR(36)      DEFAULT NULL,
  `valideAt`        DATETIME      DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`utilisateurId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: administrateur (INDEPENDENT entity — no utilisateur link)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `nom`             VARCHAR(100)  NOT NULL,
  `prenom`          VARCHAR(100)  NOT NULL,
  `email`           VARCHAR(255)  NOT NULL UNIQUE,
  `motDePasse`      VARCHAR(255)  NOT NULL,
  `permissions`     JSON          DEFAULT NULL,
  `superAdmin`      TINYINT(1)    NOT NULL DEFAULT 0,
  `actif`           TINYINT(1)    NOT NULL DEFAULT 1,
  `dernierConnexion` DATETIME     DEFAULT NULL,
  `refreshToken`    VARCHAR(500)  DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: categorie
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `categorie` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `nom`             VARCHAR(150)  NOT NULL UNIQUE,
  `slug`            VARCHAR(150)  NOT NULL UNIQUE,
  `description`     TEXT          DEFAULT NULL,
  `icone`           VARCHAR(10)   DEFAULT '📚',
  `couleur`         VARCHAR(20)   DEFAULT '#7c3aed',
  `ordre`           INT UNSIGNED  DEFAULT 0,
  `actif`           TINYINT(1)    NOT NULL DEFAULT 1,
  `parentId`        CHAR(36)      DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parentId`) REFERENCES `categorie`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: cours
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cours` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `titre`           VARCHAR(300)  NOT NULL,
  `slug`            VARCHAR(300)  NOT NULL UNIQUE,
  `description`     TEXT          NOT NULL,
  `descriptionCourte` VARCHAR(500) DEFAULT NULL,
  `formateurId`     CHAR(36)      NOT NULL,
  `categorieId`     CHAR(36)      NOT NULL,
  `niveau`          ENUM('DEBUTANT','INTERMEDIAIRE','AVANCE') NOT NULL DEFAULT 'DEBUTANT',
  `langue`          VARCHAR(10)   NOT NULL DEFAULT 'fr',
  `prix`            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `prixPromo`       DECIMAL(10,2) DEFAULT NULL,
  `imageUrl`        VARCHAR(500)  DEFAULT NULL,
  `videoIntro`      VARCHAR(500)  DEFAULT NULL,
  `statut`          ENUM('BROUILLON','EN_REVISION','PUBLIE','ARCHIVE','REJETE') NOT NULL DEFAULT 'BROUILLON',
  `dureeTotal`      INT UNSIGNED  DEFAULT 0 COMMENT 'Duration in minutes',
  `nombreLecons`    INT UNSIGNED  DEFAULT 0,
  `objectifs`       JSON          DEFAULT NULL,
  `prerequis`       JSON          DEFAULT NULL,
  `tags`            JSON          DEFAULT NULL,
  `noteGlobale`     DECIMAL(3,2)  DEFAULT 0.00,
  `nombreAvis`      INT UNSIGNED  DEFAULT 0,
  `nombreInscrits`  INT UNSIGNED  DEFAULT 0,
  `nombreCertifs`   INT UNSIGNED  DEFAULT 0,
  `scoreMini`       INT UNSIGNED  DEFAULT 70 COMMENT 'Minimum score to pass (%)',
  `certifAuto`      TINYINT(1)    NOT NULL DEFAULT 1,
  `isBestseller`    TINYINT(1)    NOT NULL DEFAULT 0,
  `isFeatured`      TINYINT(1)    NOT NULL DEFAULT 0,
  `valideParId`     CHAR(36)      DEFAULT NULL,
  `valideAt`        DATETIME      DEFAULT NULL,
  `raisonRejet`     TEXT          DEFAULT NULL,
  `publishedAt`     DATETIME      DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`formateurId`)  REFERENCES `formateur`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`categorieId`)  REFERENCES `categorie`(`id`) ON DELETE RESTRICT,
  INDEX `idx_statut`    (`statut`),
  INDEX `idx_categorie` (`categorieId`),
  INDEX `idx_formateur` (`formateurId`),
  INDEX `idx_niveau`    (`niveau`),
  FULLTEXT INDEX `ft_cours` (`titre`, `description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: section (course sections/chapters)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `section` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `coursId`         CHAR(36)      NOT NULL,
  `titre`           VARCHAR(300)  NOT NULL,
  `description`     TEXT          DEFAULT NULL,
  `ordre`           INT UNSIGNED  NOT NULL DEFAULT 0,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`coursId`) REFERENCES `cours`(`id`) ON DELETE CASCADE,
  INDEX `idx_cours_section` (`coursId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: lecon
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `lecon` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `sectionId`       CHAR(36)      NOT NULL,
  `coursId`         CHAR(36)      NOT NULL,
  `titre`           VARCHAR(300)  NOT NULL,
  `type`            ENUM('VIDEO','DOCUMENT','QUIZ','DEVOIR','TEXTE') NOT NULL DEFAULT 'VIDEO',
  `contenu`         LONGTEXT      DEFAULT NULL,
  `videoUrl`        VARCHAR(500)  DEFAULT NULL,
  `videoDuree`      INT UNSIGNED  DEFAULT 0 COMMENT 'Duration in seconds',
  `ordre`           INT UNSIGNED  NOT NULL DEFAULT 0,
  `estGratuit`      TINYINT(1)    NOT NULL DEFAULT 0,
  `xpRecompense`    INT UNSIGNED  DEFAULT 10,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`sectionId`) REFERENCES `section`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)   REFERENCES `cours`(`id`)   ON DELETE CASCADE,
  INDEX `idx_section_lecon` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: ressource (downloadable resources per lesson)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `ressource` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `leconId`         CHAR(36)      NOT NULL,
  `nom`             VARCHAR(255)  NOT NULL,
  `type`            ENUM('PDF','VIDEO','ZIP','IMAGE','AUTRE') NOT NULL,
  `url`             VARCHAR(500)  NOT NULL,
  `tailleMo`        DECIMAL(8,2)  DEFAULT 0.00,
  `ordre`           INT UNSIGNED  DEFAULT 0,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`leconId`) REFERENCES `lecon`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: inscription (ASSOCIATION CLASS between Candidat & Cours)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `inscription` (
  `id`                    CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `candidatId`            CHAR(36)      NOT NULL,
  `coursId`               CHAR(36)      NOT NULL,
  `dateInscription`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `progression`           DECIMAL(5,2)  NOT NULL DEFAULT 0.00 COMMENT 'Percentage 0-100',
  `statut`                ENUM('ACTIF','COMPLETE','ABANDONNE','SUSPENDU') NOT NULL DEFAULT 'ACTIF',
  `dateFin`               DATETIME      DEFAULT NULL,
  `certificatDisponible`  TINYINT(1)    NOT NULL DEFAULT 0,
  `derniereConnexionCours` DATETIME     DEFAULT NULL,
  `scoreGlobal`           DECIMAL(5,2)  DEFAULT NULL,
  `tempsTotal`            INT UNSIGNED  DEFAULT 0 COMMENT 'Total time in minutes',
  `leconActuelle`         CHAR(36)      DEFAULT NULL,
  `createdAt`             DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`             DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_inscription` (`candidatId`, `coursId`),
  FOREIGN KEY (`candidatId`) REFERENCES `candidat`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)    REFERENCES `cours`(`id`)    ON DELETE CASCADE,
  INDEX `idx_candidat_inscription` (`candidatId`),
  INDEX `idx_cours_inscription`    (`coursId`),
  INDEX `idx_statut_inscription`   (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: progression_lecon (track per-lesson completion)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `progression_lecon` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `inscriptionId`   CHAR(36)      NOT NULL,
  `leconId`         CHAR(36)      NOT NULL,
  `complete`        TINYINT(1)    NOT NULL DEFAULT 0,
  `tempsVisionne`   INT UNSIGNED  DEFAULT 0 COMMENT 'Seconds watched',
  `completedAt`     DATETIME      DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_prog_lecon` (`inscriptionId`, `leconId`),
  FOREIGN KEY (`inscriptionId`) REFERENCES `inscription`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`leconId`)       REFERENCES `lecon`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: evaluation (quiz or exam definition)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `coursId`         CHAR(36)      NOT NULL,
  `leconId`         CHAR(36)      DEFAULT NULL,
  `titre`           VARCHAR(300)  NOT NULL,
  `type`            ENUM('QUIZ','EXAMEN_FINAL','DEVOIR') NOT NULL,
  `description`     TEXT          DEFAULT NULL,
  `dureeMinutes`    INT UNSIGNED  DEFAULT 30,
  `scoreMinimum`    INT UNSIGNED  DEFAULT 70,
  `tentativesMax`   INT UNSIGNED  DEFAULT 3,
  `melangerQuestions` TINYINT(1) DEFAULT 1,
  `afficherReponses` TINYINT(1)  DEFAULT 1,
  `ordre`           INT UNSIGNED  DEFAULT 0,
  `actif`           TINYINT(1)    NOT NULL DEFAULT 1,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`coursId`) REFERENCES `cours`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`leconId`) REFERENCES `lecon`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: question_quiz
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `question_quiz` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `evaluationId`    CHAR(36)      NOT NULL,
  `texte`           TEXT          NOT NULL,
  `type`            ENUM('QCM','VRAI_FAUX','REPONSE_COURTE','QCM_MULTIPLE') NOT NULL DEFAULT 'QCM',
  `options`         JSON          DEFAULT NULL COMMENT '[{text, isCorrect, explanation}]',
  `reponseCorrecte` TEXT          DEFAULT NULL,
  `explication`     TEXT          DEFAULT NULL,
  `points`          INT UNSIGNED  DEFAULT 1,
  `ordre`           INT UNSIGNED  DEFAULT 0,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`evaluationId`) REFERENCES `evaluation`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: soumission_evaluation (quiz/exam submissions)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `soumission_evaluation` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `evaluationId`    CHAR(36)      NOT NULL,
  `candidatId`      CHAR(36)      NOT NULL,
  `inscriptionId`   CHAR(36)      NOT NULL,
  `score`           DECIMAL(5,2)  DEFAULT 0.00,
  `reponses`        JSON          DEFAULT NULL,
  `statut`          ENUM('EN_COURS','SOUMIS','CORRIGE','EXPIRE') NOT NULL DEFAULT 'EN_COURS',
  `tentativeNum`    INT UNSIGNED  DEFAULT 1,
  `reussi`          TINYINT(1)    DEFAULT 0,
  `tempsUtilise`    INT UNSIGNED  DEFAULT 0 COMMENT 'Seconds used',
  `correction`      JSON          DEFAULT NULL,
  `commentaireFormateur` TEXT    DEFAULT NULL,
  `noteFormateur`   DECIMAL(5,2)  DEFAULT NULL,
  `commenceAt`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `soumisAt`        DATETIME      DEFAULT NULL,
  `corrigeAt`       DATETIME      DEFAULT NULL,
  FOREIGN KEY (`evaluationId`)  REFERENCES `evaluation`(`id`)  ON DELETE CASCADE,
  FOREIGN KEY (`candidatId`)    REFERENCES `candidat`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`inscriptionId`) REFERENCES `inscription`(`id`) ON DELETE CASCADE,
  INDEX `idx_evaluation_soumission` (`evaluationId`),
  INDEX `idx_candidat_soumission`   (`candidatId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: devoir (assignment submission with file upload)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `devoir` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `evaluationId`    CHAR(36)      NOT NULL,
  `candidatId`      CHAR(36)      NOT NULL,
  `inscriptionId`   CHAR(36)      NOT NULL,
  `contenu`         TEXT          DEFAULT NULL,
  `fichiers`        JSON          DEFAULT NULL COMMENT '[{name, url, size}]',
  `urlGithub`       VARCHAR(500)  DEFAULT NULL,
  `statut`          ENUM('SOUMIS','EN_CORRECTION','CORRIGE','RETOURNE') NOT NULL DEFAULT 'SOUMIS',
  `note`            DECIMAL(5,2)  DEFAULT NULL,
  `feedback`        TEXT          DEFAULT NULL,
  `reussi`          TINYINT(1)    DEFAULT NULL,
  `soumisAt`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `corrigeAt`       DATETIME      DEFAULT NULL,
  FOREIGN KEY (`evaluationId`)  REFERENCES `evaluation`(`id`)  ON DELETE CASCADE,
  FOREIGN KEY (`candidatId`)    REFERENCES `candidat`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`inscriptionId`) REFERENCES `inscription`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: certificat
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `certificat` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `inscriptionId`   CHAR(36)      NOT NULL UNIQUE,
  `candidatId`      CHAR(36)      NOT NULL,
  `coursId`         CHAR(36)      NOT NULL,
  `codeVerification` VARCHAR(50)  NOT NULL UNIQUE,
  `qrCodeUrl`       VARCHAR(500)  DEFAULT NULL,
  `pdfUrl`          VARCHAR(500)  DEFAULT NULL,
  `scoreObtenu`     DECIMAL(5,2)  NOT NULL,
  `dureeCompletion` INT UNSIGNED  DEFAULT 0 COMMENT 'Days to complete',
  `dateEmission`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateExpiration`  DATETIME      DEFAULT NULL,
  `valide`          TINYINT(1)    NOT NULL DEFAULT 1,
  FOREIGN KEY (`inscriptionId`) REFERENCES `inscription`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`candidatId`)    REFERENCES `candidat`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)       REFERENCES `cours`(`id`)       ON DELETE CASCADE,
  INDEX `idx_code_verif` (`codeVerification`),
  INDEX `idx_candidat_certif` (`candidatId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: avis (course reviews)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `avis` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `coursId`         CHAR(36)      NOT NULL,
  `candidatId`      CHAR(36)      NOT NULL,
  `note`            TINYINT UNSIGNED NOT NULL CHECK (`note` BETWEEN 1 AND 5),
  `commentaire`     TEXT          DEFAULT NULL,
  `utile`           INT UNSIGNED  DEFAULT 0,
  `modere`          TINYINT(1)    NOT NULL DEFAULT 0,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_avis` (`coursId`, `candidatId`),
  FOREIGN KEY (`coursId`)    REFERENCES `cours`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`candidatId`) REFERENCES `candidat`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: notification
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `notification` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `utilisateurId`   CHAR(36)      NOT NULL,
  `type`            ENUM('INSCRIPTION','PROGRESSION','QUIZ','CERTIFICAT','MESSAGE','SYSTEME','PROMO') NOT NULL,
  `titre`           VARCHAR(200)  NOT NULL,
  `message`         TEXT          NOT NULL,
  `lien`            VARCHAR(500)  DEFAULT NULL,
  `lue`             TINYINT(1)    NOT NULL DEFAULT 0,
  `data`            JSON          DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`utilisateurId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE,
  INDEX `idx_notif_user`  (`utilisateurId`),
  INDEX `idx_notif_lue`   (`lue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: message (messaging system)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `message` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `expediteurId`    CHAR(36)      NOT NULL,
  `destinataireId`  CHAR(36)      NOT NULL,
  `coursId`         CHAR(36)      DEFAULT NULL,
  `contenu`         TEXT          NOT NULL,
  `lu`              TINYINT(1)    NOT NULL DEFAULT 0,
  `fichiers`        JSON          DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`expediteurId`)   REFERENCES `utilisateur`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`destinataireId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)        REFERENCES `cours`(`id`)       ON DELETE SET NULL,
  INDEX `idx_expediteur`    (`expediteurId`),
  INDEX `idx_destinataire`  (`destinataireId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: support_ticket
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `support_ticket` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `utilisateurId`   CHAR(36)      NOT NULL,
  `sujet`           VARCHAR(300)  NOT NULL,
  `message`         TEXT          NOT NULL,
  `categorie`       ENUM('TECHNIQUE','PAIEMENT','COURS','CERTIFICAT','AUTRE') NOT NULL DEFAULT 'AUTRE',
  `priorite`        ENUM('BASSE','NORMALE','HAUTE','URGENTE') NOT NULL DEFAULT 'NORMALE',
  `statut`          ENUM('OUVERT','EN_COURS','RESOLU','FERME') NOT NULL DEFAULT 'OUVERT',
  `reponse`         TEXT          DEFAULT NULL,
  `assigneA`        CHAR(36)      DEFAULT NULL,
  `resolvedAt`      DATETIME      DEFAULT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`utilisateurId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: badge (gamification)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `badge` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `nom`             VARCHAR(100)  NOT NULL UNIQUE,
  `description`     VARCHAR(300)  NOT NULL,
  `icone`           VARCHAR(10)   NOT NULL DEFAULT '🏆',
  `type`            ENUM('STREAK','COMPLETION','SCORE','XP','SPECIAL') NOT NULL,
  `condition`       JSON          NOT NULL,
  `xpRecompense`    INT UNSIGNED  DEFAULT 50,
  `actif`           TINYINT(1)    NOT NULL DEFAULT 1,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: utilisateur_badge (earned badges)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `utilisateur_badge` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `utilisateurId`   CHAR(36)      NOT NULL,
  `badgeId`         CHAR(36)      NOT NULL,
  `obtenueAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_user_badge` (`utilisateurId`, `badgeId`),
  FOREIGN KEY (`utilisateurId`) REFERENCES `utilisateur`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`badgeId`)       REFERENCES `badge`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: document_formateur
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `document_formateur` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `formateurId`     CHAR(36)      NOT NULL,
  `coursId`         CHAR(36)      DEFAULT NULL,
  `nom`             VARCHAR(255)  NOT NULL,
  `url`             VARCHAR(500)  NOT NULL,
  `type`            VARCHAR(50)   NOT NULL,
  `taille`          INT UNSIGNED  DEFAULT 0,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`formateurId`) REFERENCES `formateur`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)     REFERENCES `cours`(`id`)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- TABLE: liste_souhaits (wishlist)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `liste_souhaits` (
  `id`              CHAR(36)      NOT NULL DEFAULT (UUID()) PRIMARY KEY,
  `candidatId`      CHAR(36)      NOT NULL,
  `coursId`         CHAR(36)      NOT NULL,
  `createdAt`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_wishlist` (`candidatId`, `coursId`),
  FOREIGN KEY (`candidatId`) REFERENCES `candidat`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coursId`)    REFERENCES `cours`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Categories
-- ─────────────────────────────────────────────────────────────
INSERT INTO `categorie` (`id`, `nom`, `slug`, `description`, `icone`, `ordre`) VALUES
  (UUID(), 'Développement', 'developpement', 'Web, mobile, backend, frontend', '💻', 1),
  (UUID(), 'Design', 'design', 'UI/UX, Figma, graphisme', '🎨', 2),
  (UUID(), 'Data Science', 'data-science', 'Analyse de données, visualisation', '📊', 3),
  (UUID(), 'Intelligence Artificielle', 'ia-ml', 'Machine Learning, Deep Learning, NLP', '🤖', 4),
  (UUID(), 'Cybersécurité', 'cybersecurite', 'Hacking éthique, sécurité réseau', '🔐', 5),
  (UUID(), 'Cloud & DevOps', 'cloud-devops', 'AWS, Docker, Kubernetes, CI/CD', '☁️', 6),
  (UUID(), 'Business', 'business', 'Management, entrepreneuriat, finance', '💼', 7),
  (UUID(), 'Marketing Digital', 'marketing', 'SEO, réseaux sociaux, publicité', '📣', 8);

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Badges
-- ─────────────────────────────────────────────────────────────
INSERT INTO `badge` (`id`, `nom`, `description`, `icone`, `type`, `condition`, `xpRecompense`) VALUES
  (UUID(), 'Premier Pas', 'Vous êtes inscrit à votre premier cours', '🌱', 'COMPLETION', '{"coursCompletes": 0, "inscriptions": 1}', 25),
  (UUID(), 'Streak 7 Jours', 'Apprenez 7 jours consécutifs', '🔥', 'STREAK', '{"joursConsecutifs": 7}', 100),
  (UUID(), 'Streak 30 Jours', 'Apprenez 30 jours consécutifs', '💎', 'STREAK', '{"joursConsecutifs": 30}', 500),
  (UUID(), 'Quiz Master', 'Obtenez 90%+ à un quiz', '🎯', 'SCORE', '{"scoreMinimum": 90}', 150),
  (UUID(), 'Certifié', 'Obtenez votre premier certificat', '📜', 'COMPLETION', '{"certificats": 1}', 200),
  (UUID(), 'Triple Couronne', 'Terminez 3 cours', '🚀', 'COMPLETION', '{"coursCompletes": 3}', 300),
  (UUID(), '500 XP', 'Atteignez 500 points XP', '⚡', 'XP', '{"xpTotal": 500}', 50),
  (UUID(), '1000 XP', 'Atteignez 1000 points XP', '⚡', 'XP', '{"xpTotal": 1000}', 100),
  (UUID(), 'Boulimique de Savoir', 'Inscrivez-vous à 10 cours', '📚', 'COMPLETION', '{"inscriptions": 10}', 200);

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Admin
-- ─────────────────────────────────────────────────────────────
INSERT INTO `administrateur` (`id`, `nom`, `prenom`, `email`, `motDePasse`, `superAdmin`) VALUES
  (UUID(), 'Admin', 'EduNova', 'admin@edunova.com', '$2b$12$hashed_password_here', 1);

SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────────────────────────
-- USEFUL VIEWS
-- ─────────────────────────────────────────────────────────────

-- View: course statistics
CREATE OR REPLACE VIEW `v_cours_stats` AS
SELECT
  c.id,
  c.titre,
  c.slug,
  c.niveau,
  c.noteGlobale,
  c.nombreInscrits,
  c.nombreCertifs,
  c.statut,
  cat.nom AS categorie,
  CONCAT(u.prenom, ' ', u.nom) AS formateur,
  COUNT(DISTINCT i.id) AS inscriptions_actives,
  AVG(se.score) AS score_moyen_quiz
FROM cours c
JOIN categorie cat ON c.categorieId = cat.id
JOIN formateur f ON c.formateurId = f.id
JOIN utilisateur u ON f.utilisateurId = u.id
LEFT JOIN inscription i ON c.id = i.coursId AND i.statut = 'ACTIF'
LEFT JOIN soumission_evaluation se ON c.id = (
  SELECT e.coursId FROM evaluation e WHERE e.id = se.evaluationId LIMIT 1
)
GROUP BY c.id, c.titre, c.slug, c.niveau, c.noteGlobale,
         c.nombreInscrits, c.nombreCertifs, c.statut, cat.nom, formateur;

-- View: candidate learning overview
CREATE OR REPLACE VIEW `v_candidat_apprentissage` AS
SELECT
  cand.id AS candidatId,
  CONCAT(u.prenom, ' ', u.nom) AS candidat,
  u.email,
  u.xp,
  u.streakJours,
  COUNT(DISTINCT i.id) AS coursInscrits,
  COUNT(DISTINCT CASE WHEN i.statut = 'COMPLETE' THEN i.id END) AS coursTermines,
  COUNT(DISTINCT cert.id) AS certificatsObtenus,
  AVG(i.progression) AS progressionMoyenne
FROM candidat cand
JOIN utilisateur u ON cand.utilisateurId = u.id
LEFT JOIN inscription i ON cand.id = i.candidatId
LEFT JOIN certificat cert ON cand.id = cert.candidatId
GROUP BY cand.id, candidat, u.email, u.xp, u.streakJours;
