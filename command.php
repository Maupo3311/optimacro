<?php

use App\App;
use App\Container;
use App\Service\OptionsService;
use App\Service\OutputWriter;

require './vendor/autoload.php';

try {
    $container = Container::init();
    /** @var OptionsService $optionsService */
    $optionsService = $container->get(OptionsService::class);
    $options = $optionsService->initOptions();
    /** @var App $app */
    $app = $container->get(App::class);
    $app->run($options);
    exit(0);
} catch (Throwable $exception) {
    OutputWriter::line($exception->getMessage());
    exit(255);
}
