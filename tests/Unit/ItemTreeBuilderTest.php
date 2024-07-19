<?php

namespace Tests\Unit;

use App\Exception\ItemNotFoundException;
use App\Exception\RelationNotSetException;
use App\Service\ItemTreeBuilder;
use Generator;
use Tests\Support\Helper\Item;
use Tests\Support\Helper\ItemCollection;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class ItemTreeBuilderTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @throws ItemNotFoundException
     * @throws RelationNotSetException
     */
    #[DataProvider('validProvider')]
    public function testPopulateTreeInCollectionValid(ItemCollection $collection, ItemCollection $expectedCollection): void
    {
        $itemTreeBuilder = new ItemTreeBuilder();
        $itemTreeBuilder->populateTreeInCollection($collection);

        $this->assertEquals($expectedCollection, $collection);
    }

    #[DataProvider('throwableProvider')]
    public function testPopulateTreeInCollectionThrowable(ItemCollection $collection, string $expectedThrowable): void
    {
        $itemTreeBuilder = new ItemTreeBuilder();
        $this->tester->expectThrowable($expectedThrowable, function() use ($itemTreeBuilder, $collection) {
            $itemTreeBuilder->populateTreeInCollection($collection);
        });
    }

    protected function throwableProvider(): Generator
    {
        $notFoundItem = new Item(
            'Элемент с невалидным родителем',
            'Изделия и компоненты',
            'Несуществующий элемент',
            ''
        );
        yield [(new ItemCollection())->addItem($notFoundItem), ItemNotFoundException::class];

        $totalItem = new Item('Total', 'Изделия и компоненты', '', '');
        $withoutRelationItem = new Item(
            'Прямой компонент без Relation',
            'Прямые компоненты',
            'Total',
            ''
        );
        yield [(new ItemCollection())->addItem($totalItem)->addItem($withoutRelationItem), RelationNotSetException::class];
    }

    /**
     * @throws ItemNotFoundException
     */
    protected function validProvider(): Generator
    {
        $collection = (new ItemCollection())
            ->addItem(new Item('Total', 'Изделия и компоненты', '', ''))
            ->addItem(new Item('ПВЛ', 'Изделия и компоненты', 'Total', ''))
            ->addItem(new Item('Тележка Б25.#2', 'Прямые компоненты', 'ПВЛ', 'Тележка Б25'))
            ->addItem(new Item('Тележка Б25', 'Изделия и компоненты', 'Total', ''))
            ->addItem(new Item('Стандарт.#5', 'Варианты комплектации', 'Тележка Б25', ''));

        $expectedCollection = clone $collection;
        $totalItem = $expectedCollection->getItem('Total');
        $totalItem
            ->addChild(
                $expectedCollection->getItem('ПВЛ')->addChild(
                    $expectedCollection->getItem('Тележка Б25.#2')->setChildren(
                        $expectedCollection->getItem('Тележка Б25')->getChildren()
                    )
                )
            )
            ->addChild(
                $expectedCollection->getItem('Тележка Б25')->addChild(
                    $expectedCollection->getItem('Стандарт.#5')
                )
            );

        $expectedCollection->addRootItem($totalItem);

        yield [$collection, $expectedCollection];
    }
}
