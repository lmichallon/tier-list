# Documentation API (Swagger UI)

## Objectif

- Exposer une **documentation REST lisible** à partir de l’OpenAPI générée par API Platform.
- Proposer une **interface Swagger UI** accessible en local.

## URLs utiles

- Spec OpenAPI (JSON) : `http://localhost:8000/api/docs.jsonopenapi`
- UI Swagger : `http://localhost:8080/swagger-ui/index.html`

## Mise en place

### 1) API Platform (OpenAPI)

Le fichier `api/config/packages/api_platform.yaml` active les formats de documentation :

```yaml
docs_formats:
  jsonopenapi: ['application/vnd.openapi+json']
  json: ['application/json']
  jsonld: ['application/ld+json']
  html: ['text/html']
```

**Commentaire :** `/api/docs.jsonopenapi` est le format que Swagger UI consomme.

### 2) Swagger UI via Docker

Dans `docker-compose.yml`, un service `swagger-ui` est ajouté :

```yaml
swagger-ui:
  image: swaggerapi/swagger-ui:v5.17.14
  ports:
    - "8080:8080"
  environment:
    URL: "http://host.docker.internal:8000/api/docs.jsonopenapi"
    BASE_URL: "/swagger-ui"
```

**Commentaire :** `BASE_URL` permet l’accès sur `/swagger-ui/index.html` au lieu de `/`.

## Sécurité & CORS

- L’endpoint `/api/docs*` est public (configuration `security.yaml`).
- Le CORS autorise `localhost` et `127.0.0.1` via `CORS_ALLOW_ORIGIN`.

## Démarrage

```bash
# API (HTTP recommandé pour swagger en local)
symfony serve:start --no-tls

# UI Swagger
docker compose up -d swagger-ui
```

## Dépannage

**Swagger UI affiche Petstore :**
- Vérifier que `URL` est bien défini dans `docker-compose.yml`.
- Relancer le container : `docker compose up -d --force-recreate swagger-ui`.

**Erreur “Format json non supporté” :**
- Utiliser `http://localhost:8000/api/docs.jsonopenapi`.
