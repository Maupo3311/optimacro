<?php

namespace App;

use App\DTO\Options;
use App\Exception\CannotOpenFileException;
use App\Exception\FileNotWrittenException;
use App\Exception\ItemNotFoundException;
use App\Exception\NoRootItemsException;
use App\Exception\RelationNotSetException;
use App\Service\CSVService;
use App\Service\FileHandler;
use App\Service\ItemCollectionSerializer;
use App\Service\ItemTreeBuilder;
use App\Service\OutputWriter;

class App
{
    public function __construct(
        private readonly CSVService $CSVService,
        private readonly ItemTreeBuilder $itemTreeBuilder,
        private readonly ItemCollectionSerializer $serializer,
        private readonly FileHandler $fileHandler
    ) {
        // empty
    }

    /**
     * @throws ItemNotFoundException
     * @throws RelationNotSetException
     * @throws NoRootItemsException
     * @throws CannotOpenFileException
     * @throws FileNotWrittenException
     */
    public function run(Options $options): void
    {
        $itemCollection = $this->CSVService->getItemCollectionFromCSV($options->getInputFile());
        $this->itemTreeBuilder->populateTreeInCollection($itemCollection);
        $outputContent = $this->serializer->serialize($itemCollection);
        $this->fileHandler->writeFile($options->getOutputFile(), $outputContent);
        OutputWriter::line('Дерево успешно построено!');
    }
}
