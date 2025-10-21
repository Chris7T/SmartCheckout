<?php

namespace App\Enums;

enum CustomerTypeEnum: int
{
    case CLIENT = 1;
    case EMPLOYEE = 2;

    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::EMPLOYEE => 'Employee',
        };
    }
}
