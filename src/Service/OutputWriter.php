<?php

namespace App\Service;

abstract class OutputWriter
{
    public static function line(string $text): void
    {
        echo $text . "\n";
    }
}
