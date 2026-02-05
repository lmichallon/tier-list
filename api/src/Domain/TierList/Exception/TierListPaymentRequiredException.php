<?php

namespace App\Domain\TierList\Exception;

final class TierListPaymentRequiredException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Payment required to fill tier list.');
    }
}
