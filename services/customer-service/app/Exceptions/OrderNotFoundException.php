<?php

namespace App\Exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    protected $message = 'Order not found.';
    protected $code = 404;
}
