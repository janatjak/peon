<?php

declare(strict_types=1);

namespace Peon\Domain\Project\Event;

use Peon\Domain\Project\Value\ProjectId;

final class ProjectAdded
{
    public function __construct(
        public readonly ProjectId $projectId
    ) {}
}
