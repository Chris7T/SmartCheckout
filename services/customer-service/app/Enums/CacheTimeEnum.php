<?php

namespace App\Enums;

enum CacheTimeEnum: int
{
    case ONE_HOUR = 3600;
    case ONE_DAY = 86400;
    case ONE_WEEK = 604800;
    case ONE_MONTH = 2592000;
}

