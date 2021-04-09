<?php

declare(strict_types=1);

namespace PHPMate\Domain\Composer;

use PHPMate\Domain\FileSystem\WorkingDirectory;

final class Composer
{
    public function __construct(
        private ComposerBinary $composerBinary,
    ) {}


    /**
     * @throws ComposerJsonFileMissing
     */
    public function installInWorkingDirectory(WorkingDirectory $workingDirectory): void
    {
        if ($workingDirectory->fileExists('composer.json') === false) {
            throw new ComposerJsonFileMissing();
        }

        // TODO: remove --ignore-platform-reqs once we have supported environment for the project
        $this->composerBinary->execInWorkingDirectory($workingDirectory,'install --ignore-platform-reqs');
    }
}
