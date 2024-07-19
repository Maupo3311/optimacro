<?php

namespace App\Exception;

use Exception;

class CannotOpenFileException extends Exception
{
    public function __construct(string $filepath)
    {
        parent::__construct("Невозможно открыть файл '{$filepath}'");
    }
}
