-- Roop Shree Construction — PostgreSQL schema
-- Local: auto-applied on first `docker compose up` via docker-entrypoint-initdb.d
-- Production: run ONCE manually via cPanel PostgreSQL / phpPgAdmin. Do not auto-run in prod.

CREATE TYPE project_type AS ENUM ('Flat', 'Plot', 'Villa', 'Commercial');
CREATE TYPE project_status AS ENUM ('Available', 'Sold', 'Coming Soon');

CREATE TABLE admin_users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
  id SERIAL PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  type project_type NOT NULL,
  location VARCHAR(150),
  price VARCHAR(100),               -- store as text: "Rs 18.31 Lakh onwards" (flexible)
  size VARCHAR(100),                -- e.g. "1200 sq. yard"
  status project_status DEFAULT 'Available',
  rera_number VARCHAR(100),
  short_desc VARCHAR(300),          -- card preview text
  full_desc TEXT,                   -- detail page content
  featured BOOLEAN DEFAULT FALSE,
  brochure_path VARCHAR(255),       -- path to uploaded PDF
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE project_images (
  id SERIAL PRIMARY KEY,
  project_id INT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
  image_path VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0
);

-- updated_at auto-update trigger (Postgres has no native ON UPDATE CURRENT_TIMESTAMP)
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = CURRENT_TIMESTAMP;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_projects_updated_at
BEFORE UPDATE ON projects
FOR EACH ROW EXECUTE PROCEDURE set_updated_at();

-- Seed admin_users manually with a bcrypt-hashed password (generated via PHP password_hash()),
-- never typed in plaintext anywhere. Not done here — see deploy/seed step.
