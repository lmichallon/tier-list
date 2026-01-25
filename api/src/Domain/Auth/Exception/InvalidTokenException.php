<?php

namespace App\Domain\Auth\Exception;

use DomainException;

final class InvalidTokenException extends DomainException
{
    public function __construct(string $message = 'Invalid access token.')
    {
        parent::__construct($message);
    }
}
