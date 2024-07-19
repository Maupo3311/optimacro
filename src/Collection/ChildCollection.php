<?php

namespace App\Collection;

use App\DTO\Item;

class ChildCollection extends AbstractCollection
{
    public function addItem(Item $item): static
    {
        $this->items[] = $item;
        return $this;
    }
}
