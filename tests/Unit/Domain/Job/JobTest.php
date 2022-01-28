<?php
declare(strict_types=1);

namespace Peon\Tests\Unit\Domain\Job;

use Lcobucci\Clock\FrozenClock;
use Peon\Domain\Job\Job;
use Peon\Domain\Job\Exception\JobHasFinishedAlready;
use Peon\Domain\Job\Exception\JobHasNotStartedYet;
use Peon\Domain\Job\Exception\JobHasStartedAlready;
use Peon\Domain\Job\Value\JobId;
use Peon\Domain\Process\Value\ProcessResult;
use Peon\Domain\Project\Value\ProjectId;
use PHPUnit\Framework\TestCase;

final class JobTest extends TestCase
{
    public function testJobCanBeScheduled(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        self::assertNotNull($job->scheduledAt);
        self::assertNull($job->startedAt);
        self::assertNull($job->failedAt);
        self::assertNull($job->succeededAt);
    }


    public function testJobCanStart(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);
        $job->start($clock);

        self::assertNotNull($job->scheduledAt);
        self::assertNotNull($job->startedAt);
        self::assertNull($job->failedAt);
        self::assertNull($job->succeededAt);
    }


    public function testJobCanSucceed(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);
        $job->start($clock);
        $job->succeeds($clock);

        self::assertNotNull($job->scheduledAt);
        self::assertNotNull($job->startedAt);
        self::assertNull($job->failedAt);
        self::assertNotNull($job->succeededAt);
    }


    public function testJobCanFail(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);
        $job->start($clock);
        $job->fails($clock);

        self::assertNotNull($job->scheduledAt);
        self::assertNotNull($job->startedAt);
        self::assertNotNull($job->failedAt);
        self::assertNull($job->succeededAt);
    }


    public function testJobCanNotBeStartedTwice(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $job->start($clock);

        $this->expectException(JobHasStartedAlready::class);
        $job->start($clock);
    }


    public function testJobCanNotSuccessWithoutStarting(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $this->expectException(JobHasNotStartedYet::class);
        $job->succeeds($clock);
    }


    public function testJobCanNotFailWithoutStarting(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $this->expectException(JobHasNotStartedYet::class);
        $job->fails($clock);
    }


    public function testJobCanNotFailWhenAlreadyFailed(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $job->start($clock);
        $job->fails($clock);
        $this->expectException(JobHasFinishedAlready::class);
        $job->fails($clock);
    }


    public function testJobCanNotFailWhenAlreadySucceeded(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $job->start($clock);
        $job->succeeds($clock);
        $this->expectException(JobHasFinishedAlready::class);
        $job->fails($clock);
    }


    public function testJobCanNotSucceedWhenAlreadyFailed(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $job->start($clock);
        $job->fails($clock);
        $this->expectException(JobHasFinishedAlready::class);
        $job->succeeds($clock);
    }


    public function testJobCanNotSucceedWhenAlreadySucceeded(): void
    {
        $clock = FrozenClock::fromUTC();
        $job = $this->createJob($clock);

        $job->start($clock);
        $job->succeeds($clock);
        $this->expectException(JobHasFinishedAlready::class);
        $job->succeeds($clock);
    }


    private function createJob(FrozenClock $clock): Job
    {
        return new Job(
            new JobId(''),
            new ProjectId(''),
            '',
            ['command'],
            $clock,
        );
    }
}
