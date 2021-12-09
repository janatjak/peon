<?php

declare(strict_types=1);

namespace PHPMate\Ui\ReadModel\ProjectDetail;

use Cron\CronExpression;
use DateTimeImmutable;
use Lorisleiva\CronTranslator\CronTranslator;
use PHPMate\Ui\ReadModel\JobStatus;

final class ReadRecipe
{
    public string $schedule = '0 */2 * * *';

    private string $lastJobStatus = JobStatus::SCHEDULED;


    public function __construct(
        public string $title,
        public string $recipeName,
        public string|null $lastJobId,
        public DateTimeImmutable $lastJobScheduledAt,
        public DateTimeImmutable|null $lastJobStartedAt,
        public DateTimeImmutable|null $lastJobSucceededAt,
        public DateTimeImmutable|null $lastJobFailedAt,
        public string|null $lastJobMergeRequestUrl,
    ) {
        if ($lastJobFailedAt !== null) {
            $this->lastJobStatus = JobStatus::FAILED;
        } elseif ($lastJobSucceededAt !== null) {
            $this->lastJobStatus = JobStatus::SUCCEEDED;
        } elseif ($lastJobStartedAt !== null) {
            $this->lastJobStatus = JobStatus::IN_PROGRESS;
        }
    }


    public function getLastJobActionTime(): DateTimeImmutable
    {
        return $this->lastJobFailedAt
            ?? $this->lastJobSucceededAt
            ?? $this->lastJobStartedAt
            ?? $this->lastJobScheduledAt;
    }


    public function isJobPending(): bool
    {
        return $this->lastJobStatus === JobStatus::SCHEDULED;
    }


    public function isJobInProgress(): bool
    {
        return $this->lastJobStatus === JobStatus::IN_PROGRESS;
    }


    public function hasJobSucceeded(): bool
    {
        return $this->lastJobStatus === JobStatus::SUCCEEDED;
    }


    public function hasJobFailed(): bool
    {
        return $this->lastJobStatus === JobStatus::FAILED;
    }


    public function getHumanReadableCron(): string
    {
        return CronTranslator::translate($this->schedule);
    }


    /**
     * @throws \Exception
     */
    public function getNextRunTime(): DateTimeImmutable
    {
        $cronExpression = new CronExpression($this->schedule);
        $nextRun = $cronExpression->getNextRunDate();

        return DateTimeImmutable::createFromMutable($nextRun);
    }
}