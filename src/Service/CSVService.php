<?php

namespace App\Service;

use App\Collection\ItemCollection;
use App\DTO\Item;
use App\Exception\CannotOpenFileException;
use Keboola\Csv\CsvReader;
use Keboola\Csv\Exception;

class CSVService
{
    public function __construct(private readonly FileHandler $fileHandler)
    {
        // empty
    }

    /**
     * @throws CannotOpenFileException
     */
    public function getItemCollectionFromCSV(string $filename): ItemCollection
    {
        $collection = new ItemCollection();
        foreach ($this->createReader($filename) as $row) {
            $collection->addItem(new Item(...$row));
        }
        return $collection;
    }

    /**
     * @throws CannotOpenFileException
     */
    private function createReader(string $filename): CsvReader
    {
        $filepath = $this->fileHandler->getFilepath($filename);
        try {
            return new CsvReader(
                file: $filepath,
                delimiter: ';',
                skipLines: 1
            );
        } catch (Exception) {
            throw new CannotOpenFileException($filepath);
        }
    }
}
