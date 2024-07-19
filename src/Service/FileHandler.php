<?php

namespace App\Service;

use App\Exception\FileNotWrittenException;

class FileHandler
{
    public function __construct(private readonly string $uploadDirectory)
    {
        // empty
    }

    public function getFilepath(string $filename): string
    {
        return $this->uploadDirectory . '/' . $filename;
    }

    /**
     * Записать файл со всеми необходимыми директориями
     * @throws FileNotWrittenException
     */
    public function writeFile(string $filename, string $content): void
    {
        $filepath = $this->getFilepath($filename);
        $parts = explode('/', $filepath);
        array_pop($parts);
        $directory = '';
        foreach ($parts as $part) {
            $directory .= '/' . $part;
            if (!is_dir($directory)) {
                mkdir($directory);
            }
        }
        if (false === file_put_contents($filepath, $content)) {
            throw new FileNotWrittenException($filepath);
        }
    }
}
