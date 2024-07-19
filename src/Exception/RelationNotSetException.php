<?php

namespace App\Exception;

use App\Enum\ItemTypeEnum;
use Exception;

class RelationNotSetException extends Exception
{
    public function __construct(string $itemName)
    {
        $message = sprintf(
            'Элемент "%s" имеет тип %s, но в нем не указан Relation',
            $itemName,
            ItemTypeEnum::DIRECT_COMPONENTS->value
        );
        parent::__construct($message);
    }
}
