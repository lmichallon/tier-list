# Contexte & besoins (implémentés)

## Fonctionnalités présentes

- Authentification : **inscription**, **connexion**, **déconnexion**, **refresh**.
- Récupération des logos : `GET /api/logos`.
- Récupération d’une tier list utilisateur : `GET /api/tierlists/me`.
- Déplacement d’un logo dans un tier : `POST /api/tierlists/move`.
- Génération d’un **PDF de statistiques globales** : `POST /api/tierlists/pdf`.

## Règles métier présentes

- Un logo est identifié par un `company` unique (unicité en base).
- Un **use case** d’ajout existe (`LogoApplicationService`) avec contrainte **max 10 logos**.
  - Ce use case est présent dans le code, mais **l’API POST n’est pas exposée** dans les resources actuelles.

## Contraintes non-fonctionnelles

- API stateless (JWT).
- Séparation des responsabilités (hexagonal).
- Génération PDF côté backend avec stockage externe.

## Contexte technique

- **Backend** : Symfony 8, API Platform, Doctrine ORM.
- **Frontend** : Next.js 16, React 19, DnD Kit.
- **Storage PDF** : Minio via Flysystem (S3 compatible).

