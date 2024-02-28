<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public const ERROR_CODE = 401;

    public const ERROR_MESSAGE = 'Не авторизован';

    public function __construct()
    {
        $this->code = static::ERROR_CODE;
        $this->message = static::ERROR_MESSAGE;
        parent::__construct($this->message, $this->code);
    }
}
