<?php

namespace App\Collection;

use App\DTO\Item;

abstract class AbstractCollection
{
    protected array $items = [];

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    abstract public function addItem(Item $item): static;
}
