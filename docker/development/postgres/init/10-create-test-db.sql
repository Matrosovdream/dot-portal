-- Creates an isolated database for the automated test suite (`php artisan test`),
-- on the same Postgres instance as dev. The test suite (tests/TestCase.php) points
-- the pgsql connection at this database so tests never touch dev data.
--
-- Postgres runs files in /docker-entrypoint-initdb.d/ only when the data volume is
-- empty (first init). It runs as the POSTGRES_USER, so the new DB is owned by them.
-- Idempotent via \gexec so re-running against an existing cluster is harmless.
SELECT 'CREATE DATABASE dotportal_test'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'dotportal_test')\gexec
