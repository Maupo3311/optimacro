<?php

namespace App\Exception;

use Exception;

class ItemNotFoundException extends Exception
{
    public function __construct(string $itemName)
    {
        parent::__construct("Не найдено элемента с именем '{$itemName}'");
    }
}
