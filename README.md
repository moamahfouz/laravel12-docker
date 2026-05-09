# Test App

A Laravel 12 application skeleton running in a multi-service Docker environment with an integrated Node.js Express server, queue workers, and task scheduling.

## Tech Stack

| Service | Technology |
|---------|-----------|
| Backend | PHP 8.3, Laravel 12 |
| Frontend | Vite, Tailwind CSS 4 |
| Database | MySQL 8.4 |
| Cache / Queue | Redis |
| Web Server | Nginx (Alpine) |
| Secondary API | Node.js 22, Express 4 |
| Queue Worker | Laravel Horizon |
| Task Scheduler | Laravel Scheduler |

## Docker Services

The application is orchestrated via Docker Compose with the following services:

| Service | Container Name | Port | Description |
|---------|---------------|------|-------------|
| `app` | `laravel12-app` | — | PHP-FPM 8.3 application container |
| `nginx` | `laravel12-nginx` | `8000:80` | Web server / reverse proxy |
| `mysql` | `laravel12-mysql` | `3306:3306` | MySQL 8.4 database |
| `redis` | `laravel12-redis` | `6380:6379` | Redis cache & queue broker |
| `node` | `laravel12-node` | `3000:3000` | Express.js API server |
| `queue` | — | — | Laravel Horizon queue worker |
| `scheduler` | — | — | Laravel schedule worker |

All services communicate over the shared `laravel` Docker network.

### Database Credentials

| Variable | Value |
|----------|-------|
| Database | `testdb` |
| Root Password | `root` |
| User | `laravel` |
| Password | `112277` |

## PHP Dockerfile

The `docker/php/Dockerfile` builds the app image from `php:8.3-fpm` and installs:

- System dependencies: `git`, `curl`, `zip`, `unzip`, `libpng-dev`, `libonig-dev`, `libxml2-dev`, `libzip-dev`, `libicu-dev`, `nodejs`, `npm`
- PHP extensions: `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`, `intl`, `zip`
- Redis PHP extension via PECL
- Composer (latest)
- Non-root user `appuser` (UID 1000)

## Node.js Dockerfile

The `docker/node/Dockerfile` builds the secondary API from `node:22-alpine`:

- Installs dependencies from `node/package.json`
- Exposes port `3000`
- Runs `npm start` to launch the Express server

## Composer Configuration

### Required Packages

| Package | Version | Purpose |
|---------|---------|---------|
| `php` | `^8.2` | PHP runtime |
| `laravel/framework` | `^12.0` | Laravel framework |
| `laravel/horizon` | `^5.46` | Queue dashboard & worker management |
| `laravel/tinker` | `^2.10.1` | Interactive REPL |

### Dev Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `fakerphp/faker` | `^1.23` | Data seeding |
| `laravel/pail` | `^1.2.2` | Log tailing |
| `laravel/pint` | `^1.24` | Code styling |
| `laravel/sail` | `^1.41` | Local Docker development |
| `mockery/mockery` | `^1.6` | Mocking library |
| `nunomaduro/collision` | `^8.6` | Error reporting |
| `phpunit/phpunit` | `^11.5.50` | Testing framework |

### Composer Scripts

```bash
# Full setup (install deps, generate key, migrate, build assets)
composer run setup

# Local development (runs server, queue, logs, and Vite concurrently)
composer run dev

# Run PHPUnit tests
composer run test
```

## Getting Started

### Prerequisites

- Docker & Docker Compose

### Installation

1. Clone the repository and navigate into it:

```bash
cd test-app
```

2. Copy the environment file:

```bash
cp .env.example .env
```

3. Start the Docker services:

```bash
docker compose up -d --build
```

4. Install PHP dependencies:

```bash
docker compose exec app composer install
```

5. Generate the application key:

```bash
docker compose exec app php artisan key:generate
```

6. Run database migrations:

```bash
docker compose exec app php artisan migrate
```

7. Install and build frontend assets:

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

### Access the Application

| Service | URL |
|---------|-----|
| Laravel App | http://localhost:8000 |
| Node API | http://localhost:3000 |
| Node Health Check | http://localhost:3000/health |
| MySQL | `localhost:3306` |
| Redis | `localhost:6380` |

### Useful Commands

```bash
# Fill users table with 10 test users
docker compose exec app php artisan fill:users-db

# View Horizon dashboard (if route is registered)
# Visit /horizon in your browser

# Tail application logs
docker compose exec app php artisan pail

# Run tests
docker compose exec app php artisan test
```

## Project Structure Highlights

```
.
├── app/
│   ├── Console/Commands/FillUserCommand.php   # Artisan command to seed test users
│   ├── Jobs/UpdateUsersTableJob.php            # Queue job to verify last 10 users
│   └── Models/User.php                         # Eloquent User model
├── docker/
│   ├── php/Dockerfile                          # PHP-FPM image
│   ├── node/Dockerfile                         # Node.js image
│   └── nginx/default.conf                      # Nginx vhost
├── node/
│   ├── server.js                               # Express API entry point
│   └── package.json                            # Node dependencies (Express 4)
├── composer.json                               # PHP dependencies & scripts
├── docker-compose.yml                          # Full stack orchestration
└── package.json                                # Frontend dependencies (Vite, Tailwind)
```

## Key Features

- **Multi-service Docker stack** — PHP-FPM, Nginx, MySQL, Redis, Node.js, Horizon, and Scheduler running together.
- **Queue processing** — Laravel Horizon manages background jobs via Redis.
- **Task scheduling** — Dedicated scheduler container runs `php artisan schedule:work`.
- **Express API** — Secondary Node.js server available alongside the Laravel application.
- **Artisan commands** — Includes `fill:users-db` for quick test data generation.
- **Queue jobs** — `UpdateUsersTableJob` demonstrates queued updates to the users table.

## License

This project is open-sourced software licensed under the MIT license.
