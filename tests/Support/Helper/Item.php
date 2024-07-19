<?php

namespace Tests\Support\Helper;

use App\DTO\Item as AppItem;

class Item extends AppItem
{
    public function __clone(): void
    {
        $this->setChildren(clone $this->getChildren());
    }
}
