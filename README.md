# Roop Shree Construction — Website

Real estate marketing site for Roop Shree Construction (Jodhpur), replacing the current WordPress site at roopshreeconstruction.com.

## Stack

- PHP 8.3, PostgreSQL (via PDO, prepared statements only)
- Vanilla HTML/CSS/JS — no frameworks, no Node.js
- Target hosting: Bluehost Shared Prime

## Local development (Docker)

```
docker compose up --build
```

- Site: http://localhost:8000
- PostgreSQL: localhost:5432 (db: `roopshree`, user: `roopshree` — see `docker-compose.yml`)
- Schema in `public_html/sql/schema.sql` is auto-applied on first container start.
- Copy `public_html/includes/db.example.php` to `db.php` (gitignored) if it doesn't already exist locally; the committed defaults match `docker-compose.yml`.

## Structure

```
public_html/
  assets/          css, js, images
  uploads/         admin-uploaded project images/PDFs (gitignored)
  includes/        shared header/footer/db connection
  admin/           login-gated CRUD panel for property listings
  sql/schema.sql   PostgreSQL schema (run manually in production)
```

## Branching

One branch per build phase, merged into `main` once tested locally end-to-end. See project plan for phase breakdown and security requirements (PDO prepared statements, hashed passwords, session auth, validated file uploads — all non-negotiable).
