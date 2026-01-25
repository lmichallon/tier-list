<?php

namespace App\Domain\Logo\Repository;

use App\Domain\Logo\Entity\Logo;

interface LogoRepositoryInterface
{
    /**
     * @return Logo[]
     */
    public function findAll(): array;

    public function save(Logo $logo): void;
}
