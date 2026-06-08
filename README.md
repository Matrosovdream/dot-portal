# DOT Portal

A b2b portal for managing DOT-regulated trucking businesses — companies,
drivers, vehicles, subscriptions, service requests and orders. The backend
exposes an API consumed by a Vue 3 + PrimeVue SPA, running on Docker (PHP 8.3
FPM, MySQL, Nginx, phpMyAdmin).

---

## 🚀 Installation

### 1. Start Docker containers

```bash
docker compose up -d --build
```

### 2. Install dependencies & seed the database

Enter the app container and run:

```bash
docker exec -it laravel-app bash

composer install
php artisan key:generate
php artisan migrate --seed
exit
```

---

## 🔐 Login credentials

Every seeded account uses the password **`123456`**.

| Role          | Email               | Notes       |
| ------------- | ------------------- | ----------- |
| Administrator | `admin@gmail.com`   | Full access |
| Manager       | `manager@gmail.com` |             |
| Company       | `company@gmail.com` |             |
| Driver        | `driver@gmail.com`  |             |

To reseed only the users and roles:

```bash
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
```

Source: [database/seeders/UserSeeder.php](database/seeders/UserSeeder.php),
[database/seeders/RoleSeeder.php](database/seeders/RoleSeeder.php).

---

## 🌐 Access

- Laravel App: [http://localhost:8000](http://localhost:8000)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)

### phpMyAdmin credentials

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
make dev-seed                                     # reference + base data first
make dev-dummy                                    # generate dummy data
make dev-dummy ARGS="--fresh"                      # wipe dummy data, then regenerate
make dev-dummy ARGS="--companies=20 --seed=42"     # 20 companies, reproducible output
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

- Ensure `.env` has the correct DB configuration:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

- Default ports — App: `8000`, phpMyAdmin: `8080`, MySQL: `3306`
