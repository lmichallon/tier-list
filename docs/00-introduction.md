# Introduction

Le projet **Tier List** permet de classer des logos d’entreprises dans des niveaux (S, A, B, C, D) avec authentification et persistance côté serveur. Le backend suit une architecture hexagonale pour isoler le domaine et limiter le couplage aux frameworks.

Ce dossier décrit **uniquement ce qui est présent dans le dépôt** : structure, choix techniques, flux principaux, et extraits de code.

## Périmètre couvert par ce dossier

- **Backend** : Symfony 8 + API Platform, Doctrine ORM, architecture hexagonale.
- **Frontend** : Next.js 16 (React 19) avec hooks applicatifs.
- **PDF** : génération de statistiques via Dompdf, stockage Minio (S3).
- **Sécurité** : JWT + refresh token, cookies HttpOnly.

