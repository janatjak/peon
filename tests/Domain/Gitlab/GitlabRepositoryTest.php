<?php
declare(strict_types=1);

namespace PHPMate\Tests\Domain\Gitlab;

use PHPMate\Domain\Gitlab\GitlabAuthentication;
use PHPMate\Domain\Gitlab\GitlabRepository;
use PHPMate\Domain\Gitlab\InvalidGitlabRepositoryUri;
use PHPUnit\Framework\TestCase;

class GitlabRepositoryTest extends TestCase
{
    public function testGetAuthenticatedRepositoryUri(): void
    {
        $authentication = new GitlabAuthentication('janmikes', 'PAT');
        $repository = new GitlabRepository('https://gitlab.com/janmikes/repository.git', $authentication);

        self::assertSame('https://janmikes:PAT@gitlab.com/janmikes/repository.git', (string) $repository->getAuthenticatedRepositoryUri());
    }

    public function testGetAuthenticatedRepositoryUriUProtocol(): void
    {
        $this->expectException(InvalidGitlabRepositoryUri::class);

        $authentication = new GitlabAuthentication('janmikes', 'PAT');
        new GitlabRepository('git@gitlab.com:janmikes/repository.git', $authentication);
    }

    public function testGetProject(): void
    {
        $authentication = new GitlabAuthentication('janmikes', 'PAT');
        $repository = new GitlabRepository('https://gitlab.com/janmikes/repository.git', $authentication);

        self::assertSame('janmikes/repository', $repository->getProject());
    }
}
