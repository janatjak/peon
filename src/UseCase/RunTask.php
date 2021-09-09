<?php

declare(strict_types=1);

namespace PHPMate\UseCase;

use JetBrains\PhpStorm\Immutable;
use PHPMate\Domain\Task\TaskId;

#[Immutable]
final class RunTask
{
    public function __construct(
        public TaskId $taskId
    ) {}
}
