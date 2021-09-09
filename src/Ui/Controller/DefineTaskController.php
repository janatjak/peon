<?php

declare(strict_types=1);

namespace PHPMate\Ui\Controller;

use PHPMate\Domain\Project\ProjectId;
use PHPMate\Domain\Project\ProjectNotFound;
use PHPMate\Domain\Project\ProjectsCollection;
use PHPMate\Ui\Form\DefineTaskFormData;
use PHPMate\Ui\Form\DefineTaskFormType;
use PHPMate\UseCase\DefineTaskCommand;
use PHPMate\UseCase\DefineTask;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DefineTaskController extends AbstractController
{
    public function __construct(
        private ProjectsCollection $projectsCollection,
        private MessageBusInterface $commandBus
    ) {}


    #[Route(path: '/define-task/{projectId}', name: 'define_task')]
    public function __invoke(string $projectId, Request $request): Response
    {
        try {
            $activeProject = $this->projectsCollection->get(new ProjectId($projectId));
        } catch (ProjectNotFound) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DefineTaskFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DefineTaskFormData $data */
            $data = $form->getData();

            $this->commandBus->dispatch(
                new DefineTaskCommand(
                    $activeProject->projectId,
                    $data->name,
                    $data->getCommandsAsArray()
                )
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('define_task.html.twig', [
            'define_task_form' => $form->createView(),
        ]);
    }
}
