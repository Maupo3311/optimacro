<?php

namespace App\Collection;

use App\DTO\Item;
use App\Exception\ItemNotFoundException;

class ItemCollection extends AbstractCollection
{
    private array $rootItems = [];

    public function addRootItem(Item $rootItem): static
    {
        $this->rootItems[] = $rootItem;
        return $this;
    }

    public function addItem(Item $item): static
    {
        $this->items[$item->getName()] = $item;
        return $this;
    }

    /**
     * @return Item[]
     */
    public function getRootItems(): array
    {
        return $this->rootItems;
    }

    /**
     * @throws ItemNotFoundException
     */
    public function getItem(string $name): Item
    {
        return $this->items[$name] ?? throw new ItemNotFoundException($name);
    }
}
