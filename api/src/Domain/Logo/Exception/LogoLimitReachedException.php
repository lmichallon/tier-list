<?php

namespace App\Domain\Logo\Exception;

use DomainException;

final class LogoLimitReachedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Maximum number of logos reached.');
    }
}
