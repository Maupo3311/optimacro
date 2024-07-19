<?php

namespace App\Exception;

use Exception;

class OptionNotSetException extends Exception
{
    public function __construct(string $option, string $description)
    {
        parent::__construct("Параметр -{$option} не задан ({$description})");
    }
}
