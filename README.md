# Tier List – Guide de lancement

Ce README explique comment lancer **backend**, **frontend**, **MinIO** et **Swagger UI** en local.

## Prérequis

- PHP **>= 8.4** + Composer
- Node.js **>= 20**
- PostgreSQL (ex: Supabase) ou une base locale
- Docker (pour MinIO + Swagger UI)
- Symfony CLI (optionnel, mais pratique)

## Backend (Symfony API)

### 1) Installer les dépendances

```bash
cd api
composer install
```

### 2) Variables d’environnement

Créer `api/.env.local` avec au minimum :

```
DATABASE_URL="postgresql://<user>:<password>@<host>:<port>/<db>?sslmode=require&serverVersion=16&charset=utf8"
JWT_SECRET=change_me
LOGO_DEV_API_KEY=pk_...

MINIO_ENDPOINT=http://localhost:9000
MINIO_PUBLIC_ENDPOINT=http://localhost:9000
MINIO_KEY=minio
MINIO_SECRET=minio123
MINIO_BUCKET=pdf-exports

STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_PRICE_ID=price_...
STRIPE_SUCCESS_URL=http://localhost:3000?payment=success
STRIPE_CANCEL_URL=http://localhost:3000?payment=cancel

CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
```

### 3) Lancer le serveur

```bash
symfony serve:start --no-tls
```

Alternative :

```bash
php -S localhost:8000 -t public
```

API disponible sur : `http://localhost:8000/api`

## Frontend (Next.js)

### 1) Installer les dépendances

```bash
cd ui
npm install
```

### 2) Variables d’environnement (optionnel)

Créer `ui/.env.local` si besoin :

```
NEXT_PUBLIC_API_BASE_URL=http://localhost:8000/api
```

### 3) Lancer le frontend

```bash
npm run dev
```

UI disponible sur : `http://localhost:3000`

## MinIO (stockage PDF)

```bash
docker compose up -d minio
```

- API S3 : `http://localhost:9000`
- Console : `http://localhost:9001`

> Créer le bucket défini dans `MINIO_BUCKET` (ex: `pdf-exports`) si besoin.

## Swagger UI (docs API)

```bash
docker compose up -d swagger-ui
```

Swagger UI : `http://localhost:8080/swagger-ui/index.html`

Spec OpenAPI : `http://localhost:8000/api/docs.jsonopenapi`

## Documentation projet

Le dossier `docs/` contient le dossier d’architecture :

```bash
docs/README.md
```

