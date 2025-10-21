<?php

namespace App\Exceptions;

use Exception;

class CustomerNotFoundException extends Exception
{
    protected $message = 'Customer not found.';
    protected $code = 404;
}
