<?php

namespace App\Service;

use App\Collection\ItemCollection;
use App\Enum\ItemTypeEnum;
use App\Exception\ItemNotFoundException;
use App\Exception\RelationNotSetException;

class ItemTreeBuilder
{
    /**
     * @throws ItemNotFoundException
     * @throws RelationNotSetException
     */
    public function populateTreeInCollection(ItemCollection $collection): void
    {
        foreach ($collection->getItems() as $item) {
            if (empty($item->getParent())) {
                $collection->addRootItem($item);
                continue;
            }
            $parentItem = $collection->getItem($item->getParent());
            $parentItem->addChild($item);
            if ($item->getType() === ItemTypeEnum::DIRECT_COMPONENTS->value) {
                if (empty($item->getRelation())) {
                    throw new RelationNotSetException($item->getName());
                }
                $relationItem = $collection->getItem($item->getRelation());
                $item->setChildren($relationItem->getChildren());
            }
        }
    }
}
