<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PHPMate\Domain\Git\BranchNameProvider;
use PHPMate\Infrastructure\Git\RandomPostfixBranchNameProvider;

return static function(ContainerConfigurator $configurator): void
{
    $services = $configurator->services();

    $services->defaults()
        ->autoconfigure()
        ->autowire()
        ->public(); // Allow access services via container in tests


    $services->set(RandomPostfixBranchNameProvider::class);
    $services->alias(BranchNameProvider::class, RandomPostfixBranchNameProvider::class);
};