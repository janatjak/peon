<?php

use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedPrivateMethodRector::class);
};
