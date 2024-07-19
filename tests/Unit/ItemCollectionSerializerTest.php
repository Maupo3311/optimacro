<?php

namespace Tests\Unit;

use App\Service\ItemCollectionSerializer;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Generator;
use ReflectionException;
use ReflectionMethod;
use Tests\Support\Helper\Item;
use Tests\Support\Helper\ItemCollection;
use Tests\Support\UnitTester;

class ItemCollectionSerializerTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @throws ReflectionException
     */
    #[DataProvider('validProvider')]
    public function testNormalizeCollection(ItemCollection $collection, array $expectedResult): void
    {
        $serializer = new ItemCollectionSerializer();
        $method = new ReflectionMethod($serializer, 'normalizeCollection');
        $normalizedCollection = $method->invoke($serializer, $collection);
        $this->tester->assertEquals($expectedResult, $normalizedCollection);
    }

    protected function validProvider(): Generator
    {
        $allRootItemsCallback = static function () {
            $rootItems = [
                new Item('Элемент 1', 'Тип 1', '', ''),
                new Item('Элемент 2', 'Тип 2', '', ''),
                new Item('Элемент 3', 'Тип 3', '', ''),
            ];
            $collection = new ItemCollection();
            foreach ($rootItems as $rootItem) {
                $collection->addItem($rootItem)->addRootItem($rootItem);
            }
            $expectedResult = [
                [
                    'itemName' => 'Элемент 1',
                    'parent' => '',
                    'children' => [],
                ],
                [
                    'itemName' => 'Элемент 2',
                    'parent' => '',
                    'children' => [],
                ],
                [
                    'itemName' => 'Элемент 3',
                    'parent' => '',
                    'children' => [],
                ],
            ];
            return [$collection, $expectedResult];
        };
        $itemWithChildrenCallback = static function () {
            $item3 = new Item('Дочерний элемент 3', 'Тип 3', 'Дочерний элемент 2', '');
            $item2 = (new Item('Дочерний элемент 2', 'Тип 2', 'Элемент 1', ''))->addChild($item3);
            $item1 = (new Item('Элемент 1', 'Тип 1', '', ''))->addChild($item2);
            $collection = (new ItemCollection())
                ->addItem($item1)
                ->addItem($item2)
                ->addItem($item3)
                ->addRootItem($item1);
            $expectedResult = [
                [
                    'itemName' => 'Элемент 1',
                    'parent' => '',
                    'children' => [
                        [
                            'itemName' => 'Дочерний элемент 2',
                            'parent' => 'Элемент 1',
                            'children' => [
                                [
                                    'itemName' => 'Дочерний элемент 3',
                                    'parent' => 'Дочерний элемент 2',
                                    'children' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            return [$collection, $expectedResult];
        };

        yield $allRootItemsCallback();
        yield $itemWithChildrenCallback();
    }
}
