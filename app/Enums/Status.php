<?php

namespace App\Enums;

enum Status: int
{
    case COMPLETE = 1;
    case CHARGEBACK = 2;
}
