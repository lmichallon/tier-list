<?php

namespace App\Domain\Logo\Repository;

use App\Domain\Logo\Entity\Logo;

interface LogoRepositoryInterface
{

    public function find(string $id): Logo;

    /**
     * @return Logo[]
     */
    public function findAll(): array;

    public function save(Logo $logo): void;

    public function existsByCompany(string $company): bool;
    public function count(): int;
}
