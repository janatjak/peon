<?php

declare(strict_types=1);

namespace PHPMate\Ui\Controller;

use PHPMate\Domain\Cookbook\Exception\RecipeNotFound;
use PHPMate\Domain\Cookbook\Value\RecipeName;
use PHPMate\Domain\GitProvider\Exception\GitProviderCommunicationFailed;
use PHPMate\Domain\Project\Exception\ProjectNotFound;
use PHPMate\Domain\Project\Exception\RecipeAlreadyEnabledForProject;
use PHPMate\Domain\Project\Value\ProjectId;
use PHPMate\Packages\Enum\InvalidEnumValue;
use PHPMate\Packages\MessageBus\Command\CommandBus;
use PHPMate\UseCase\EnableRecipeWithBaselineForProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EnableRecipeWithBaselineForProjectController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {}


    /**
     * @throws GitProviderCommunicationFailed
     */
    #[Route(path: '/projects/{projectId}/recipe/{recipeName}/enable-with-baseline', name: 'project_enable_recipe_with_baseline')]
    public function __invoke(string $projectId, string $recipeName): Response
    {
        try {
            $this->commandBus->dispatch(
                new EnableRecipeWithBaselineForProject(
                    RecipeName::fromString($recipeName),
                    new ProjectId($projectId)
                )
            );
        } catch (ProjectNotFound | InvalidEnumValue | RecipeNotFound) {
            throw $this->createNotFoundException();
        } catch (RecipeAlreadyEnabledForProject) {
            // Do nothing
        }

        return $this->redirectToRoute('cookbook', [
            'projectId' => $projectId,
        ]);
    }
}
