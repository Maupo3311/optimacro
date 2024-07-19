<?php

namespace App\Service;

use App\DTO\Options;
use App\Exception\OptionNotSetException;
use LogicException;

class OptionsService
{
    private const INPUT_FILE_OPTION = 'i';
    private const OUTPUT_FILE_OPTION = 'o';

    /**
     * @throws OptionNotSetException
     */
    public function initOptions(): Options
    {
        $options = getopt(sprintf('%s:%s:', self::INPUT_FILE_OPTION, self::OUTPUT_FILE_OPTION));
        return new Options(
            $this->getValue(self::INPUT_FILE_OPTION, $options),
            $this->getValue(self::OUTPUT_FILE_OPTION, $options)
        );
    }

    private function getDescription(string $option): string
    {
        return match ($option) {
            self::INPUT_FILE_OPTION => 'Путь до CSV файла с данными. Путь должен быть относительно /upload директории',
            self::OUTPUT_FILE_OPTION => 'Путь, по которому будет сгенерирован результирующий JSON. Путь должен быть относительно /upload директории',
            default => throw new LogicException('Неизвестный параметр ' . $option)
        };
    }

    /**
     * @throws OptionNotSetException
     */
    private function getValue(string $option, array $options): string
    {
        $value = $options[$option] ?? null;
        if (null === $value) {
            throw new OptionNotSetException($option, $this->getDescription($option));
        }
        return $value;
    }
}
