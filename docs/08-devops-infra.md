# DevOps & Infrastructure

## Lancement local

### Backend

```bash
cd api
symfony server:start --no-tls
```

### Frontend

```bash
cd ui
npm run dev
```

### Base locale (optionnelle)

Un `compose.yaml` est présent pour Postgres local :

```bash
cd api
docker compose up -d
```

## Storage PDF (Minio via Flysystem)

- **Flysystem** est configuré dans `api/config/packages/flysystem.yaml`.
- Un client S3 Minio est déclaré dans `api/config/services.yaml`.

### Extrait de code (storage)

```php
// api/src/Infrastructure/Storage/Minio/MinioPdfStorage.php
public function store(string $path, string $content): void
{
    $this->minioStorage->write($path, $content, ['ContentType' => 'application/pdf']);
}

public function getUrl(string $path): string
{
    return sprintf('%s/%s/%s', rtrim($this->publicEndpoint, '/'), $this->bucket, $path);
}
```

**Commentaire :** l’URL est construite pour un bucket public (pas de pré-signature).

## Variables d’environnement utilisées

```
MINIO_ENDPOINT=...
MINIO_KEY=...
MINIO_SECRET=...
MINIO_BUCKET=...
MINIO_PUBLIC_ENDPOINT=...
DATABASE_URL=...
```

## CI

Aucun pipeline CI n’est défini dans le dépôt.

