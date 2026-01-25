<?php

namespace App\Domain\User\Exception;

use DomainException;

final class UserAlreadyExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User already exists.');
    }
}
