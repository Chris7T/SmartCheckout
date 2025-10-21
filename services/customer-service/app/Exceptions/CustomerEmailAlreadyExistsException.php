<?php

namespace App\Exceptions;

use Exception;

class CustomerEmailAlreadyExistsException extends Exception
{
    protected $message = 'Email already exists.';
    protected $code = 422;
    private string $email;

    public function __construct(string $email = '')
    {
        parent::__construct($this->message);
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email ?? '';
    }
}
