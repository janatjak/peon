<?php

declare(strict_types=1);

namespace PHPMate\Dashboard\UseCases\Task;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class DefineTask
{
    /**
     * @param array<string> $scripts
     */
    public function __construct(public string $name, public array $scripts)
    {
    }
}
