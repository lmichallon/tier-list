<?php

namespace App\Domain\Logo\Exception;

use DomainException;

final class LogoAlreadyExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Logo already exists.');
    }
}
