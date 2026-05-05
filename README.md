# Oasis Backend

A Laravel API backend running on PHP-FPM + Nginx + PostgreSQL, fully containerized with Docker.

---

## Stack

| Service | Image                 | Description                |
| ------- | --------------------- | -------------------------- |
| `app`   | `php:8.4-fpm-alpine`  | PHP-FPM application server |
| `nginx` | `nginx:stable-alpine` | Web server / reverse proxy |
| `db`    | `postgres:16-alpine`  | PostgreSQL database        |

---

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and Docker Compose
- No local PHP or Composer required

---

## Project Structure (Docker-relevant files)

```
├── Dockerfile
├── compose.yaml               # Production-safe base config
├── compose.override.yaml      # Dev-only config (gitignored)
├── nginx.conf                 # Nginx server block
├── start.sh                   # Container entrypoint script
└── .env                       # Environment variables (gitignored)
```

---

## How it Works

### Single image, two environments

One `Dockerfile` and one `compose.yaml` serve both dev and production. A gitignored `compose.override.yaml` adds dev-only behaviour (full source mount) and is merged automatically by Docker Compose when present.

### Volume strategy

The setup uses named volumes to solve a fundamental Docker problem: bind-mounting `./:/var/www` in dev would wipe out `vendor/` and other build artifacts installed during the image build. Named volumes shield those paths:

| Volume            | Purpose                                                          |
| ----------------- | ---------------------------------------------------------------- |
| `vendor`          | Composer dependencies — owned by Docker, not the host (dev only) |
| `storage`         | Laravel storage directory — writable, persisted                  |
| `bootstrap-cache` | Laravel bootstrap cache — writable, persisted                    |
| `public-vol`      | Built public assets — shared between `app` and `nginx`           |
| `pgdata`          | Postgres data                                                    |
| `nginx-logs`      | Nginx access/error logs                                          |

### How `public/` reaches Nginx

Nginx needs access to `public/index.php` but runs in a separate container. `start.sh` copies `/var/www/public/` into a shared named volume (`public-vol`) on every container start. Nginx mounts that same volume as its web root.

---

## Local Development Setup

### 1. Clone the repo

```bash
git clone https://github.com/your-org/oasis-backend.git
cd oasis-backend
```

### 2. Set up your environment file

```bash
cp .env.example .env
```

Fill in the required values. Key ones for Docker:

```env
APP_ENV=local
APP_KEY=        # generated in step 5
DB_HOST=db      # must match the compose service name
DB_PORT=5432
DB_DATABASE=oasis
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. Create the Compose override file

Create `compose.override.yaml` in the project root. This file is gitignored and only applies locally:

```yaml
services:
    app:
        volumes:
            - ./:/var/www
            - vendor:/var/www/vendor # shields vendor/ from the host mount
            - storage:/var/www/storage
            - bootstrap-cache:/var/www/bootstrap/cache

    nginx:
        volumes:
            - ./nginx.conf:/etc/nginx/conf.d/default.conf
            - ./:/var/www
            - nginx-logs:/var/log/nginx

volumes:
    vendor:
```

> **Why is this not committed?** The override mounts your local source code for live editing. In production only the image-built code runs — `compose.yaml` alone is safe to use as-is on the server.

### 4. Build and start

```bash
docker compose up --build
```

Docker Compose automatically merges `compose.override.yaml` when it exists. The app will be available at `http://localhost:8010`.

### 5. Generate app key

```bash
docker compose exec app php artisan key:generate
```

Then restart so the cached config picks it up:

```bash
docker compose restart app
```

### 6. Run migrations

```bash
docker compose exec app php artisan migrate
```

---

## Common Commands

```bash
# Start (override merged automatically in dev)
docker compose up --build

# Start in background
docker compose up -d --build

# Shell into the app container
docker compose exec app sh

# Run artisan commands
docker compose exec app php artisan <command>

# Run composer
docker compose exec app composer <command>

# View logs
docker compose logs app
docker compose logs nginx

# Stop everything
docker compose down

# Stop and wipe all volumes (full reset)
docker compose down -v
```

---

## Production Deployment

On the production server, only `compose.yaml` exists — no override file. The full source is baked into the image at build time.

```bash
# Pull latest code
git pull

# Rebuild and restart
docker compose up --build -d

# Run migrations after deploy
docker compose exec app php artisan migrate --force
```

The Dockerfile runs `composer install --no-dev --optimize-autoloader` at build time. `start.sh` handles config, route, and view caching on every container start — no manual cache-warming needed after deploys.

---

## What `start.sh` Does

On every container start, before php-fpm launches:

1. Sets correct ownership and permissions on `storage/` and `bootstrap/cache/`
2. Copies `public/` assets into the shared `public-vol` volume so Nginx can serve them
3. Runs `php artisan config:cache`, `route:cache`, and `view:cache`
4. Starts `php-fpm`

Migrations are intentionally **not** run automatically — trigger them manually via `exec` so you control exactly when schema changes are applied.

---

## Security Notes

- Never commit `.env`
- Never commit `compose.override.yaml`
- The nginx config denies access to `.env`, `composer.json`, `.git`, and other sensitive files
- `--no-dev` is passed to Composer in the Dockerfile so dev dependencies never reach production
