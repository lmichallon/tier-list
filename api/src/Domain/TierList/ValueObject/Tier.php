<?php

namespace App\Domain\TierList\ValueObject;

enum Tier: string
{
    case UNRANKED = 'unranked';
    case S = 'S';
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
}
