<?php

namespace Tests\Support\Helper;

use App\Collection\ItemCollection as AppItemCollection;

class ItemCollection extends AppItemCollection
{
    public function __clone(): void
    {
        $clonedItems = [];
        foreach ($this->getItems() as $item) {
            $clonedItems[$item->getName()] = clone $item;
        }
        $this->items = $clonedItems;
    }
}
