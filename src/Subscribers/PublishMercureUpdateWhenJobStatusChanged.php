<?php

declare(strict_types=1);

namespace Peon\Subscribers;

use Peon\Domain\Job\Event\JobStatusChanged;
use Peon\Domain\Job\Exception\JobNotFound;
use Peon\Packages\MessageBus\Event\EventHandlerInterface;
use Peon\Ui\ReadModel\Job\ProvideReadJobById;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

final class PublishMercureUpdateWhenJobStatusChanged implements EventHandlerInterface
{
    public function __construct(
        private HubInterface $hub,
        private Environment $twig,
        private ProvideReadJobById $provideReadJobById,
    ) {}


    /**
     * @throws JobNotFound
     */
    public function __invoke(JobStatusChanged $event): void
    {
        $job = $this->provideReadJobById->provide($event->jobId);

        $this->hub->publish(
            new Update(
                'project-' . $event->projectId->id . '-overview',
                $this->twig->render('job_status_changed.stream.html.twig', [
                    'job' => $job,
                ])
            )
        );

        $this->hub->publish(
            new Update(
                'dashboard',
                $this->twig->render('dashboard.job_status_changed.stream.html.twig', [
                    'job' => $job,
                ])
            )
        );
    }
}
