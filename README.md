<p align="center"><strong>Alqima Center ERP API</strong> — Laravel 11 (PHP 8.2)</p>

## Overview

Alqima is a RESTful API for managing an educational center: students, classrooms, study materials, and packages. It is built on Laravel 11, uses Sanctum for authentication, and follows a resource/service-based structure.

## Tech Stack

-   PHP 8.2
-   Laravel 11.31
-   Laravel Sanctum
-   Spatie Laravel Permission

## Requirements

-   PHP ^8.2
-   Composer
-   A database (MySQL/PostgreSQL) with appropriate PHP extensions
-   Optional: Node.js (only if you plan to run frontend assets)

## Getting Started

1. Install dependencies

```bash
composer install
```

2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure database credentials in `.env` (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

3. Run database migrations

```bash
php artisan migrate
```

4. Start the development server

```bash
php artisan serve
```

Optional (full dev workflow: server + queue + logs + Vite)

```bash
composer run dev
```

## Authentication

Most routes are protected by `auth:sanctum`.

-   Login: `POST /api/login` → returns a token. Use it as `Authorization: Bearer <token>`.
-   Logout: `GET /api/logout`.

## API Overview

### Packages

Packages belong to a classroom and can contain multiple study materials via the pivot table `package_materials`.

Routes

```text
GET    /api/packages                # paginated index (?per_page)
POST   /api/packages                # create a package
GET    /api/packages/{package}      # show one (implicit model binding, numeric)
POST   /api/packages/{package}      # update
DELETE /api/packages/{package}      # delete
GET    /api/packages/all_materials  # list all study materials (helper)
```

Create/Update payload

```json
{
    "name": "Package A",
    "class_room_id": 1,
    "class_price": 100,
    "teacher_price": 50,
    "status": 1,
    "notes": "optional",
    "study_material_ids": [1, 2, 3]
}
```

Typical response shape (via `HttpResponse` trait)

```json
{
    "data": {
        /* resource or collection */
    },
    "message": "...",
    "type": "success",
    "code": 200
}
```

Routing notes

-   Uses implicit model binding with `{package}`. Constrain it to numbers.
-   Define the static route `/packages/all_materials` before the dynamic `/{package}` to avoid conflicts.

### Study Materials

CRUD is exposed via resource routes.

```text
GET    /api/study_materials
POST   /api/study_materials
GET    /api/study_materials/{id}
POST   /api/study_materials/{id}
DELETE /api/study_materials/{id}
```

### Class Rooms

CRUD is exposed via resource routes under `/api/class_rooms`.

## Domain Model (high-level)

-   `Package` belongs to `ClassRoom` and has many-to-many `StudyMaterial` via `package_materials`.
-   `PackageService` handles creation, update, deletion, show, and syncing of `study_material_ids`.
-   `PackageResource` formats output (`class_room`, `study_materials`, pricing, status, notes, timestamps).
-   `PackageRequest` validates input, including `study_material_ids` as an array of existing material IDs.

## Useful Commands

```bash
php artisan migrate:fresh        # rebuild the database
php artisan route:list | cat     # list routes without pager
php artisan tinker               # interact with the app
```

## Troubleshooting

-   If `/api/packages/all_materials` returns a "No query results" error, ensure it is defined before `/api/packages/{package}` and that `{package}` is constrained to numbers.
-   If `created_at` formatting errors occur, note that resources use PHP 8 nullsafe operator on timestamps.

## License

MIT
