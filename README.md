# Laravel 11 Docker Setup

A simple Docker environment for Laravel 11 with:

- PHP 8.3 (FPM)
- MySQL (latest)
- phpMyAdmin
- Nginx

---

## 🚀 Installation Instructions

### 1. Place your Laravel app

Make sure your Laravel project is located in the **root directory**, at the same level as `docker-compose.yml`.

### 2. Start Docker containers

```bash
docker compose up -d --build
```

This command builds and runs all required containers in detached mode.

### 3. Install Laravel dependencies

Enter the app container:

```bash
docker exec -it laravel-app bash
```

Then run:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
exit
```

---

## 🌐 Access

- Laravel App: [http://localhost:8000](http://localhost:8000)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)

### 🔐 phpMyAdmin Credentials

- **Server**: `mysql`
- **User**: `root`
- **Password**: `root`

---

## 🧪 Dummy Data

Generate realistic synthetic business data (companies, drivers, vehicles,
subscriptions, service requests, orders, tasks and notifications) for dev, demo
or testing. Run the reference seeders first, then generate.

### Via Make (recommended)

```bash
make dev-seed                                    # reference + base data first
make dev-dummy                                   # generate dummy data
make dev-dummy ARGS="--fresh"                     # wipe dummy data, then regenerate
make dev-dummy ARGS="--companies=20 --seed=42"    # 20 companies, reproducible output
```

A `prod-dummy` target exists for staging/demo environments
(`make prod-dummy ARGS="--fresh"`).

### Via Artisan

```bash
php artisan dummy:generate                 # default volumes
php artisan dummy:generate --fresh         # wipe existing dummy data first
php artisan dummy:generate --companies=20  # number of company accounts
php artisan dummy:generate --seed=42       # reproducible Faker output
```

All dummy records are marked by the email domain `@dummy.dotportal.test`, so
`--fresh` removes only generated data and never touches real records. Sign in with
any company account, e.g. `dummy+co-1@dummy.dotportal.test` / password `password`.

See [docs/dummy-data-generator.md](docs/dummy-data-generator.md) for the full
design, entity list and idempotency details.

---

## 📝 Notes

- Ensure `.env` file has the correct DB configuration:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

- Default ports:
  - App: `8000`
  - phpMyAdmin: `8080`
  - MySQL: `3306`

---

Happy coding! ⚡
