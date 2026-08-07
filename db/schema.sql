-- ============================================================================
-- Sama Al Tariq — MySQL schema (Hostinger)
--
-- A direct port of the previous Supabase/Postgres schema. Run once against the
-- database you created in hPanel → Databases → Management:
--
--   npm run db:init
--
-- Differences from the Postgres original, and why:
--
--  * auth.users + public.profiles are merged into one `profiles` table. Supabase
--    Auth owned the credentials there; here the Node app does, so the password
--    hash lives beside the profile instead of in a separate schema.
--  * uuid PKs are kept (CHAR(36)) so ids stay portable and non-enumerable, and
--    any exported Supabase data can be reloaded as-is.
--  * MySQL has no row-level security. Every policy in the Postgres file is
--    re-implemented in lib/repositories/* — each read is scoped by the caller's
--    session, and admin-only writes go through requireAdmin(). Nothing queries
--    these tables outside that layer.
--  * Supabase Storage becomes private files on disk under STORAGE_DIR, keeping
--    the same "<project_id>/<filename>" and "<project_id>/reports/<filename>"
--    path convention, served only through /api/files (see lib/storage.ts).
-- ============================================================================

-- 1. PROFILES — one row per user, holds credentials and role
CREATE TABLE IF NOT EXISTS profiles (
  id            CHAR(36)     NOT NULL,
  email         VARCHAR(254) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  full_name     VARCHAR(160) NULL,
  username      VARCHAR(60)  NULL,
  phone         VARCHAR(32)  NULL,
  job_title     VARCHAR(120) NULL,
  -- When true the client may download project images; otherwise view-only.
  can_download  TINYINT(1)   NOT NULL DEFAULT 0,
  role          ENUM('admin','client') NOT NULL DEFAULT 'client',
  must_change_password TINYINT(1) NOT NULL DEFAULT 0,
  last_login_at TIMESTAMP    NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY profiles_email_unique (email),
  UNIQUE KEY profiles_username_unique (username),
  KEY idx_profiles_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. PROJECTS — each assigned to one client
CREATE TABLE IF NOT EXISTS projects (
  id           CHAR(36)     NOT NULL,
  client_id    CHAR(36)     NULL,
  title        VARCHAR(200) NOT NULL,
  description  TEXT         NULL,
  location     VARCHAR(200) NULL,
  status       ENUM('Planning','In Progress','On Hold','Completed')
               NOT NULL DEFAULT 'In Progress',
  progress     TINYINT UNSIGNED NOT NULL DEFAULT 0,
  start_date   DATE         NULL,
  due_date     DATE         NULL,
  project_type VARCHAR(120) NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at   TIMESTAMP    NULL,
  PRIMARY KEY (id),
  KEY idx_projects_client (client_id),
  KEY idx_projects_deleted_created (deleted_at, created_at),
  CONSTRAINT chk_projects_progress CHECK (progress BETWEEN 0 AND 100),
  CONSTRAINT fk_projects_client FOREIGN KEY (client_id)
    REFERENCES profiles (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PROJECT IMAGES — files on disk at "<project_id>/<filename>"
CREATE TABLE IF NOT EXISTS project_images (
  id           CHAR(36)     NOT NULL,
  project_id   CHAR(36)     NOT NULL,
  storage_path VARCHAR(500) NOT NULL,
  caption      VARCHAR(300) NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_images_project (project_id, created_at),
  CONSTRAINT fk_images_project FOREIGN KEY (project_id)
    REFERENCES projects (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3b. PROJECT DOCUMENTS (reports) — "<project_id>/reports/<filename>"
CREATE TABLE IF NOT EXISTS project_documents (
  id           CHAR(36)     NOT NULL,
  project_id   CHAR(36)     NOT NULL,
  storage_path VARCHAR(500) NOT NULL,
  name         VARCHAR(255) NOT NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_documents_project (project_id, created_at),
  CONSTRAINT fk_documents_project FOREIGN KEY (project_id)
    REFERENCES projects (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. PROJECT UPDATES — status / progress timeline
CREATE TABLE IF NOT EXISTS project_updates (
  id         CHAR(36)  NOT NULL,
  project_id CHAR(36)  NOT NULL,
  note       TEXT      NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_updates_project (project_id, created_at),
  CONSTRAINT fk_updates_project FOREIGN KEY (project_id)
    REFERENCES projects (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4b. PROJECT STAGES — ordered milestones with a target completion date
CREATE TABLE IF NOT EXISTS project_stages (
  id          CHAR(36)     NOT NULL,
  project_id  CHAR(36)     NOT NULL,
  name        VARCHAR(200) NOT NULL,
  status      ENUM('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  target_date DATE         NULL,
  sort_order  INT          NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_stages_project (project_id, sort_order),
  CONSTRAINT fk_stages_project FOREIGN KEY (project_id)
    REFERENCES projects (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ENQUIRIES — public contact-form submissions from the landing page
CREATE TABLE IF NOT EXISTS enquiries (
  id            CHAR(36)     NOT NULL,
  name          VARCHAR(120) NOT NULL,
  email         VARCHAR(254) NOT NULL,
  phone         VARCHAR(32)  NULL,
  location      VARCHAR(200) NULL,
  project_type  VARCHAR(120) NULL,
  project_brief TEXT         NULL,
  ip            VARCHAR(64)  NULL,
  user_agent    VARCHAR(255) NULL,
  status        ENUM('new','read','archived') NOT NULL DEFAULT 'new',
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_enquiries_created (created_at),
  KEY idx_enquiries_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
