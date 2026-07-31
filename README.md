# Laravel Kost Backend

Backend REST API for a kost management system built with Laravel.

## Tech Stack

- Laravel 13
- PHP 8.3+
- PostgreSQL
- Laravel Sanctum
- PHPUnit

---

## Requirements

- PHP 8.3+
- Composer
- PostgreSQL

---

## Installation

Clone repository

```bash
git clone <repository-url>
cd kost-api
```

Install dependencies

```bash
composer install
```

Copy environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure database in `.env`

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kost_db
DB_USERNAME=postgres
DB_PASSWORD=password
```

Run migration

```bash
php artisan migrate
```

Start server

```bash
php artisan serve
```

API available at

```
http://127.0.0.1:8000
```

---

## Authentication

This project uses Laravel Sanctum.

After login, include token in every protected request.

```
Authorization: Bearer <token>
```

---

## API Endpoints

### Authentication

| Method | Endpoint |
|---------|----------|
| POST | /api/auth/register |
| POST | /api/auth/login |
| POST | /api/auth/logout |
| GET | /api/auth/me |

### Kost

| Method | Endpoint |
|---------|----------|
| GET | /api/kosts |
| GET | /api/kosts/{id} |
| GET | /api/owner/kosts |
| POST | /api/owner/kosts |
| PUT | /api/owner/kosts/{id} |
| DELETE | /api/owner/kosts/{id} |

### Availability

| Method | Endpoint |
|---------|----------|
| POST | /api/availability |

---

## Monthly Credit Recharge

Run manually

```bash
php artisan app:recharge-credit
```

Or schedule it monthly using Laravel Scheduler.

---

## Running Tests

```bash
php artisan test
```

---

## Code Style

```bash
vendor/bin/pint
```

---

## Project Structure

```
app/
├── Console/
├── DTOs/
├── Enums/
├── Http/
├── Models/
├── Policies/
├── Services/
└── Traits/

tests/
├── Feature/
└── Unit/
```