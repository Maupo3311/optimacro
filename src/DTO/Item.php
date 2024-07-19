<?php

namespace App\DTO;

use App\Collection\ChildCollection;

class Item
{
    private ChildCollection $children;

    public function __construct(
        private readonly string $name,
        private readonly string $type,
        private readonly ?string $parent,
        private readonly ?string $relation
    ) {
        $this->children = new ChildCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function addChild(Item $child): static
    {
        $this->children->addItem($child);
        return $this;
    }

    public function getChildren(): ChildCollection
    {
        return $this->children;
    }

    public function setChildren(ChildCollection $children): static
    {
        $this->children = $children;
        return $this;
    }
}
