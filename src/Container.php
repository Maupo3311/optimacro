<?php

namespace App;

use App\Service\CSVService;
use App\Service\FileHandler;
use App\Service\ItemCollectionSerializer;
use App\Service\ItemTreeBuilder;
use App\Service\OptionsService;
use Exception;

class Container
{
    public const PARAMETERS = [
        'upload_directory' => '/upload',
    ];

    private static self|null $instance = null;

    private array $services = [];

    private function __construct()
    {
        // empty
    }

    /**
     * Можно было бы реализовать адекватный контейнер, но как говорил дядюшка YAGNI: не строчи лишнего 😊
     * @throws Exception
     */
    public static function init(): self
    {
        if (null === self::$instance) {
            self::$instance = $container = new self();
            $container
                ->add(new OptionsService())
                ->add(new ItemTreeBuilder())
                ->add(new ItemCollectionSerializer())
                ->add(new FileHandler(self::PARAMETERS['upload_directory']))
                ->add(new CSVService($container->get(FileHandler::class)))
                ->add(new App(
                    $container->get(CSVService::class),
                    $container->get(ItemTreeBuilder::class),
                    $container->get(ItemCollectionSerializer::class),
                    $container->get(FileHandler::class)
                ));
        }

        return self::$instance;
    }

    public function add(object $service): self
    {
        $this->services[$service::class] = $service;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function get(string $class): object
    {
        return $this->services[$class] ?? throw new Exception("Сервис '{$class}' не найден");
    }
}
