<?php

namespace App\Exceptions;

use Exception;

class CommonException extends Exception
{
    protected $headers = [];

    protected $errors = [];

    public function __construct(string $message = null, string $code = null, array $errors = null, array $headers = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->headers = $headers;
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
