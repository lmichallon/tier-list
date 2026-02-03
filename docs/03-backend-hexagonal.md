# Backend – Architecture hexagonale

## Structure

```
api/src
  Domain
    Logo
    User
    Auth
    TierList
  Application
    Auth
    Pdf
    TierList
    Service
  Infrastructure
    Persistence
    Security
    Pdf
    Storage
  Interface
    Api
```

## Exemple : use case MoveLogoToTier

```php
// api/src/Application/TierList/MoveLogoToTier.php
public function execute(User $user, string $logoId, Tier $tier): void
{
    $tierList = $this->tierListRepository->findByUser($user)
        ?? new TierList($user);

    $logo = $this->logoRepository->find($logoId);
    $tierList->moveLogo($logo, $tier);

    $this->tierListRepository->save($tierList);
}
```

**Commentaire :** le use case ne connaît ni Doctrine ni l’API. Il manipule uniquement le domaine et les ports.

## Exemple : repository (port) + adapter Doctrine

```php
// api/src/Domain/Logo/Repository/LogoRepositoryInterface.php
interface LogoRepositoryInterface
{
    public function find(string $id): Logo;
    public function findAll(): array;
    public function save(Logo $logo): void;
    public function existsByCompany(string $company): bool;
    public function count(): int;
}
```

```php
// api/src/Infrastructure/Persistence/Doctrine/LogoRepository.php
public function find(string $id): Logo
{
    $logo = $this->entityManager->getRepository(Logo::class)->find($id);
    if (!$logo) {
        throw new \RuntimeException(sprintf('Logo %s not found', $id));
    }
    return $logo;
}
```

**Commentaire :** l’interface est en Domain, l’implémentation Doctrine en Infrastructure.

## Exemple : exposition API (move logo)

```php
// api/src/Interface/Api/Resource/TierListMoveResource.php
#[ApiResource(operations: [new Post(uriTemplate: '/tierlists/move', processor: MoveLogoProcessor::class)])]
final class TierListMoveResource
{
    public string $logoId;
    public string $tier;
}
```

```php
// api/src/Interface/Api/Processor/MoveLogoProcessor.php
$this->useCase->execute(
    $user,
    $data->logoId,
    Tier::from($data->tier)
);
```

**Commentaire :** l’API ne contient pas de logique métier ; elle délègue au use case.

