<?php

declare(strict_types=1);

namespace PHPMate\Domain\Job;

use PHPMate\Domain\Job\Exception\JobNotFound;
use PHPMate\Domain\Job\Value\JobId;

interface JobsCollection
{
    public function nextIdentity(): JobId;


    public function save(Job $job): void;


    /**
     * @return array<Job>
     */
    public function all(): array;


    /**
     * @throws JobNotFound
     */
    public function get(JobId $jobId): Job;
}
