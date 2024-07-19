<?php

namespace App\DTO;

class Options
{
    public function __construct(
        private readonly string $inputFile,
        private readonly string $outputFile
    ) {
        // empty
    }

    public function getInputFile(): string
    {
        return $this->inputFile;
    }

    public function getOutputFile(): string
    {
        return $this->outputFile;
    }
}
