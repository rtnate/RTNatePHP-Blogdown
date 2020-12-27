<?php

namespace RTNatePHP\Blogdown\Files;

class FileException extends \Exception
{
    public function __construct(string $message, string $filename, \Throwable $previous = null)
    {
        $exceptionMessage = 'Unable to load file: ' . $filename . '. ';
        $exceptionMessage .= $message;
        parent::__construct($exceptionMessage, 909, $previous);
    }
}