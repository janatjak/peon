<?php

declare(strict_types=1);

namespace PHPMate\Domain\FileSystem;

use League\Flysystem\FilesystemOperator;
use Nette\Utils\Random;

final class WorkingDirectoryProvider
{
    public function __construct(
        private string $baseDir,
        private FilesystemOperator $filesystemOperator
    ) {}


    public function provide(): string
    {
        $directory = $this->baseDir . '/' . Random::generate();

        $this->filesystemOperator->createDirectory($directory);

        return $directory;
    }
}