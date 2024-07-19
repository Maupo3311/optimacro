<?php

namespace App\Exception;

use Exception;

class FileNotWrittenException extends Exception
{
    public function __construct(string $filepath)
    {
        parent::__construct("Произошла ошибка при записи файла '{$filepath}'");
    }
}
