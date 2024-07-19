<?php

namespace App\Exception;

use Exception;

class NoRootItemsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Неправильно построенное дерево. Нет ни одного элемента с пустым Parent');
    }
}
