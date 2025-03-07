<?php
declare(strict_types=1);

namespace Peon\Tests\Unit\Domain\Process;

use Peon\Domain\Process\ProcessLogger;
use Peon\Domain\Process\Value\ProcessResult;
use PHPUnit\Framework\TestCase;

final class ProcessLoggerTest extends TestCase
{
    /**
     * @dataProvider provideTestProcessOutputWillBeSanitizedData
     */
    public function testProcessOutputWillBeSanitized(string $sensitiveInfo, string $expectedSanitization): void
    {
        $processResult = new ProcessResult($sensitiveInfo, 0, $sensitiveInfo, 0);

        $logger = new ProcessLogger();
        $logger->logResult($processResult);

        $logs = $logger->popLogs();

        $loggedResult = $logs[array_key_first($logs)];

        self::assertSame($expectedSanitization, $loggedResult->command);
        self::assertSame($expectedSanitization, $loggedResult->output);
    }


    /**
     * @return \Generator<array{string, string}>
     */
    public function provideTestProcessOutputWillBeSanitizedData(): \Generator
    {
        yield [
            'git clone https://username:password-password@gitlab.com/peon/peon.git .',
            'git clone https://username:****@gitlab.com/peon/peon.git .',
        ];

        yield [
            'git clone http://username:password-password@gitlab.com/peon/peon.git .',
            'git clone http://username:****@gitlab.com/peon/peon.git .',
        ];

        yield [
            'git clone git://username:password-password@gitlab.com/peon/peon.git .',
            'git clone git://username:****@gitlab.com/peon/peon.git .',
        ];

        yield [
            "git clone https://username:password-password@gitlab.com/peon/peon.git .\ngit clone https://username:password-password@gitlab.com/peon/peon.git .",
            "git clone https://username:****@gitlab.com/peon/peon.git .\ngit clone https://username:****@gitlab.com/peon/peon.git .",
        ];

        yield [
            'git clone https://username@gitlab.com/peon/peon.git .',
            'git clone https://username@gitlab.com/peon/peon.git .',
        ];
    }
}
