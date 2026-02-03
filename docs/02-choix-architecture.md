# Choix d’architecture

## Pourquoi l’hexagonal ?

- Isoler les règles métier du framework (Symfony) et des adapters (Doctrine, Minio, Dompdf).
- Faciliter les tests et l’évolution (ex : changer de storage PDF sans toucher au domaine).

## Découpage retenu

- **Domain** : entités, value objects, exceptions, interfaces de repositories et ports.
- **Application** : cas d’usage (services) et interfaces de ports.
- **Infrastructure** : implémentations concrètes (Doctrine, Minio, Dompdf).
- **Interface** : API Platform (resources, processors, providers).

## Diagramme (vue globale)

```mermaid
flowchart LR
  subgraph Interface
    API[API Platform Resources/Processors/Providers]
  end

  subgraph Application
    UC[Use Cases / Services]
  end

  subgraph Domain
    ENT[Entities / Value Objects]
    EXC[Domain Exceptions]
    REP[Repository + Port Interfaces]
  end

  subgraph Infrastructure
    DB[(Doctrine Repos)]
    MINIO[(Minio Storage)]
    PDF[Dompdf Generator]
  end

  API --> UC
  UC --> ENT
  UC --> REP
  UC --> EXC
  DB --> REP
  MINIO --> UC
  PDF --> UC
```

## Choix techniques présents

- **API Platform** pour exposer les ressources REST.
- **JWT + refresh token** pour sécuriser l’API.
- **Dompdf** pour la génération des PDFs.
- **Minio** (S3 compatible) pour stocker les PDFs.

