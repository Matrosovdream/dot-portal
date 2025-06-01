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
