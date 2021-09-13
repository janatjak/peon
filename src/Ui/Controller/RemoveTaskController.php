<?php

declare(strict_types=1);

namespace PHPMate\Ui\Controller;

use PHPMate\Domain\Task\TaskId;
use PHPMate\Domain\Task\TaskNotFound;
use PHPMate\Packages\MessageBus\Command\CommandBus;
use PHPMate\UseCase\RemoveTask;
use PHPMate\UseCase\RemoveTaskHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RemoveTaskController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {}


    #[Route(path: '/remove-task/{taskId}', name: 'remove_task')]
    public function __invoke(string $taskId): Response
    {
        try {
            $this->commandBus->dispatch(
                new RemoveTask(
                    new TaskId($taskId)
                )
            );
        } catch (TaskNotFound) {
            throw $this->createNotFoundException();
        }

        return $this->redirectToRoute('dashboard');
    }
}