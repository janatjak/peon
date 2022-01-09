<?php

declare(strict_types=1);

namespace PHPMate\Domain\Task\Event;

use PHPMate\Domain\Task\Value\TaskId;

final class TaskDeleted
{
    public function __construct(
        public readonly TaskId $taskId,
    ) {}
}
