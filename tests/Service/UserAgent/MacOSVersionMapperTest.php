<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Service\UserAgent;

use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\MacOSVersionMapper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Tests for MacOSVersionMapper.
 */
#[CoversClass(MacOSVersionMapper::class)]
class MacOSVersionMapperTest extends TestCase
{
    private MacOSVersionMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new MacOSVersionMapper();
    }

    #[DataProvider('codenameDataProvider')]
    public function testGetCodename(int $version, string $expectedCodename): void
    {
        $this->assertEquals($expectedCodename, $this->mapper->getCodename($version));
    }

    public static function codenameDataProvider(): array
    {
        return [
            [0, 'Cheetah'],
            [1, 'Puma'],
            [5, 'Leopard'],
            [6, 'Snow Leopard'],
            [10, 'Yosemite'],
            [11, 'El Capitan'],
            [15, 'Catalina'],
            [16, 'Big Sur'],
            [17, 'Monterey'],
            [18, 'Ventura'],
            [99, 'New'], // Unknown version
        ];
    }

    public function testGetDarwinMacOSMap(): void
    {
        $map = $this->mapper->getDarwinMacOSMap();
        
        $this->assertIsArray($map);
        $this->assertArrayHasKey('20.0', $map);
        $this->assertEquals('16', $map['20.0']); // Darwin 20.0 corresponds to MacOS 11 (Big Sur)
    }

    public function testGetDarwinIOSMap(): void
    {
        $map = $this->mapper->getDarwinIOSMap();
        
        $this->assertIsArray($map);
        $this->assertArrayHasKey('20.0', $map);
        $this->assertEquals('14', $map['20.0']); // Darwin 20.0 corresponds to iOS 14
    }
}
