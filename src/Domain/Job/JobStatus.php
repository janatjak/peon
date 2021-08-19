<?php

declare(strict_types=1);

namespace PHPMate\Domain\Job;

final class JobStatus
{
    public const SCHEDULED = 'scheduled';
    public const IN_PROGRESS = 'in progress';
    public const SUCCEEDED = 'succeeded';
    public const FAILED = 'failed';
}