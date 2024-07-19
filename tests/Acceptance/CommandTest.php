<?php

namespace Tests\Acceptance;

use App\Container;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Generator;
use Tests\Support\AcceptanceTester;

class CommandTest extends Unit
{
    protected AcceptanceTester $tester;

    public function testValid(): void
    {
        $dataDirectory = '../app/tests/Support/Data';
        $outputFile = 'tests/output.json';
        $this->tester->runShellCommand("php command.php -i {$dataDirectory}/input.csv -o ${outputFile}", false);
        $this->tester->seeResultCodeIs(0);
        // Не сравнивается 2 json файла напрямую, т.к при сравнении 2-х массивов информация об ошибке более читаемая
        $commandJson = json_decode(file_get_contents($this->getFilepath($outputFile)));
        $expectedJson = json_decode(file_get_contents($this->getFilepath($dataDirectory) . '/output.json'));
        $this->tester->assertEquals($expectedJson, $commandJson);
    }

    #[DataProvider('invalidProvider')]
    public function testInvalid(string $command, string $expectedResponse): void
    {
        $this->tester->runShellCommand($command, false);
        $this->tester->seeResultCodeIs(255);
        $this->tester->seeInShellOutput($expectedResponse);
    }

    protected function invalidProvider(): Generator
    {
        yield [
            'php command.php -i test.csv',
            'Параметр -o не задан (Путь, по которому будет сгенерирован результирующий JSON. Путь должен быть относительно /upload директории)',
        ];
        yield [
            'php command.php -o test.json',
            'Параметр -i не задан (Путь до CSV файла с данными. Путь должен быть относительно /upload директории)',
        ];
        yield [
            'php command.php -i %^$# -o test.json',
            'Невозможно открыть файл',
        ];
    }

    private function getFilepath(string $filename): string
    {
        return Container::PARAMETERS['upload_directory'] . '/' . $filename;
    }
}
