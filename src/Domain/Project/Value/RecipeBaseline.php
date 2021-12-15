<?php

declare(strict_types=1);

namespace PHPMate\Domain\Project\Value;

use PHPMate\Domain\Cookbook\Value\RecipeName;

final class RecipeBaseline
{
    public function __construct(
        public readonly RecipeName $recipeName,
        public readonly string $baselineHash,
    ) {}
}
