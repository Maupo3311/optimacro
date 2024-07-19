<?php

namespace App\Service;

use App\Collection\ItemCollection;
use App\DTO\Item;
use App\Exception\NoRootItemsException;

class ItemCollectionSerializer
{
    /**
     * @throws NoRootItemsException
     */
    public function serialize(ItemCollection $collection): string
    {
        return json_encode($this->normalizeCollection($collection), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @throws NoRootItemsException
     */
    private function normalizeCollection(ItemCollection $collection): array
    {
        $normalizedCollection = [];
        if (empty($collection->getRootItems())) {
            throw new NoRootItemsException();
        }
        foreach ($collection->getRootItems() as $rootItem) {
            $normalizedCollection[] = $this->normalizeItem($rootItem);
        }
        return $normalizedCollection;
    }

    private function normalizeItem(Item $item, ?Item $parent = null): array
    {
        return [
            'itemName' => $item->getName(),
            'parent' => null !== $parent ? $parent->getName() : '',
            'children' => $item->getChildren()->map(function (Item $child) use ($item) {
                return $this->normalizeItem($child, $item);
            })
        ];
    }
}
