<?php

namespace App\Domain\Auth\Exception;

use DomainException;

final class InvalidRefreshTokenException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid refresh token.');
    }
}
