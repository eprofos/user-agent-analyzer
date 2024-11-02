<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Service;

use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LoggerInterface;

/**
 * Tests for UserAgentAnalyzer service.
 */
#[CoversClass(UserAgentAnalyzer::class)]
class UserAgentAnalyzerTest extends TestCase
{
    private UserAgentAnalyzer $analyzer;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->analyzer = new UserAgentAnalyzer($this->logger);
    }

    #[DataProvider('userAgentDataProvider')]
    public function testAnalyze(string $userAgent, array $expected): void
    {
        $result = $this->analyzer->analyze($userAgent);

        $this->assertEquals($expected['os_name'], $result->getOsName(), 'OS name mismatch');
        $this->assertEquals($expected['os_version'], $result->getOsVersion(), 'OS version mismatch');
        $this->assertEquals($expected['browser_name'], $result->getBrowserName(), 'Browser name mismatch');
        $this->assertEquals($expected['browser_version'], $result->getBrowserVersion(), 'Browser version mismatch');
        $this->assertEquals($expected['device_type'], $result->getDeviceType(), 'Device type mismatch');
    }

    public static function userAgentDataProvider(): array
    {
        return [
            'Chrome on Windows 10' => [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                [
                    'os_name' => 'Windows',
                    'os_version' => 10.0,
                    'browser_name' => 'Chrome',
                    'browser_version' => 120.0,
                    'device_type' => 'desktop',
                ],
            ],
            'Safari on iOS' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 17_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
                [
                    'os_name' => 'iOS',
                    'os_version' => 17.1,
                    'browser_name' => 'Safari',
                    'browser_version' => 17.1,
                    'device_type' => 'mobile',
                ],
            ],
            'Firefox on Linux' => [
                'Mozilla/5.0 (X11; Linux x86_64; rv:120.0) Gecko/20100101 Firefox/120.0',
                [
                    'os_name' => 'unknown',
                    'os_version' => 0.0,
                    'browser_name' => 'Firefox',
                    'browser_version' => 120.0,
                    'device_type' => 'unknown',
                ],
            ],
        ];
    }
}
