<?php

namespace App\Enums;

enum CreditAmount: int
{
    case OWNER = 0;
    case REGULAR = 20;
    case PREMIUM = 40;
    case ASK_AVAILABILITY = 5;
}