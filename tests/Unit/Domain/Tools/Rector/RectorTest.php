<?php
declare(strict_types=1);

namespace Peon\Tests\Unit\Domain\Tools\Rector;

use Peon\Domain\Process\ProcessLogger;
use Peon\Domain\Process\Value\ProcessResult;
use Peon\Domain\Tools\Rector\Rector;
use Peon\Domain\Tools\Rector\RectorBinary;
use Peon\Domain\Tools\Rector\Exception\RectorCommandFailed;
use Peon\Domain\Tools\Rector\Value\RectorProcessCommandConfiguration;
use PHPUnit\Framework\TestCase;

class RectorTest extends TestCase
{
    private ProcessLogger $processLogger;


    protected function setUp(): void
    {
        parent::setUp();

        $this->processLogger = new ProcessLogger();
    }


    /**
     * @dataProvider provideTestProcessData
     */
    public function testProcess(RectorProcessCommandConfiguration $commandConfiguration, string $expectedCommand): void
    {
        $projectDirectory = '/';
        $dummyProcessResult = new ProcessResult('', 0, '', 0);

        $rectorBinary = $this->createMock(RectorBinary::class);
        $rectorBinary->expects(self::once())
            ->method('executeCommand')
            ->with(
                $projectDirectory,
                $expectedCommand
            )
            ->willReturn($dummyProcessResult);

        $rector = new Rector($rectorBinary, $this->processLogger);
        $rector->process($projectDirectory, $commandConfiguration);
    }


    public function testProcessThrowsExceptionOnNonZeroExitCode(): void
    {
        $this->expectException(RectorCommandFailed::class);

        $projectDirectory = '/';

        $rectorBinary = $this->createMock(RectorBinary::class);
        $rectorBinary->expects(self::once())
            ->method('executeCommand')
            ->willThrowException(new RectorCommandFailed());

        $rector = new Rector($rectorBinary, $this->processLogger);
        $rector->process($projectDirectory, new RectorProcessCommandConfiguration());
    }


    /**
     * @return \Generator<array{RectorProcessCommandConfiguration, string}>
     */
    public function provideTestProcessData(): \Generator
    {
        yield [
            new RectorProcessCommandConfiguration(),
            'process',
        ];

        yield [
            new RectorProcessCommandConfiguration(autoloadFile: 'autoload.php'),
            'process --autoload-file=autoload.php',
        ];

        yield [
            new RectorProcessCommandConfiguration(workingDirectory: 'directory'),
            'process --working-dir=directory',
        ];

        yield [
            new RectorProcessCommandConfiguration(config: 'config.php'),
            'process --config=config.php',
        ];

        yield [
            new RectorProcessCommandConfiguration(paths: ['src', 'app']),
            'process src app',
        ];

        yield [
            new RectorProcessCommandConfiguration('autoload.php', 'directory', 'project/config.php', ['src', 'app']),
            'process --autoload-file=autoload.php --working-dir=directory --config=project/config.php src app',
        ];
    }
}
