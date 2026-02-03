# Gestion des logos

## Ce qui est exposé

- `GET /api/logos` retourne la liste des logos enregistrés en base.
- Les logos stockent un `company` + une `imageURL`.

> Remarque : un **use case d’ajout** existe (`LogoApplicationService`) avec contraintes métier, mais **l’API POST /logos n’est pas exposée** dans les resources actuelles.

## Provider de collection

```php
// api/src/Interface/Api/Provider/LogoCollectionProvider.php
public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
{
    $logos = $this->logoRepository->findAll();

    foreach ($logos as $logo) {
        yield [
            'id' => $logo->getId(),
            'company' => $logo->getCompany(),
            'imageURL' => sprintf('%s?token=%s', $logo->getImageURL(), $this->apiKey),
        ];
    }
}
```

**Commentaire :** l’API ajoute le token `logo.dev` au moment de la réponse, en s’appuyant sur `LOGO_DEV_API_KEY`.

## Règles métier implémentées (use case)

```php
// api/src/Application/Service/LogoApplicationService.php
if ($this->logoRepository->existsByCompany($company)) {
    throw new LogoAlreadyExistsException();
}

if ($this->logoRepository->count() >= 10) {
    throw new LogoLimitReachedException();
}
```

**Commentaire :** ces règles sont appliquées par le service applicatif, indépendamment de l’API.

