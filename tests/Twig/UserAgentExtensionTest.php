<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Twig;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;
use Eprofos\UserAgentAnalyzerBundle\Twig\UserAgentExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @covers \Eprofos\UserAgentAnalyzerBundle\Twig\UserAgentExtension
 */
final class UserAgentExtensionTest extends TestCase
{
    private UserAgentAnalyzer $analyzer;

    private UserAgentExtension $extension;

    private UserAgentResult $result;

    protected function setUp(): void
    {
        $this->analyzer = $this->createMock(UserAgentAnalyzer::class);
        $this->result = $this->createMock(UserAgentResult::class);
        $this->extension = new UserAgentExtension($this->analyzer);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->extension->getFunctions();
        $this->assertCount(8, $functions);

        $expectedFunctions = [
            'is_mobile',
            'is_desktop',
            'is_tablet',
            'is_android',
            'is_windows',
            'is_linux',
            'is_ios',
            'is_macos',
        ];

        foreach ($functions as $key => $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
            $this->assertEquals($expectedFunctions[$key], $function->getName());
        }
    }

    public function testIsMobile(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('isMobile')
            ->willReturn(true);

        $this->assertTrue($this->extension->isMobile());
    }

    public function testIsDesktop(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('isDesktop')
            ->willReturn(true);

        $this->assertTrue($this->extension->isDesktop());
    }

    public function testIsTablet(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('isTablet')
            ->willReturn(true);

        $this->assertTrue($this->extension->isTablet());
    }

    public function testIsAndroid(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('getOsName')
            ->willReturn('Android');

        $this->assertTrue($this->extension->isAndroid());
    }

    public function testIsWindows(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('getOsName')
            ->willReturn('Windows');

        $this->assertTrue($this->extension->isWindows());
    }

    public function testIsLinux(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('getOsName')
            ->willReturn('Linux');

        $this->assertTrue($this->extension->isLinux());
    }

    public function testIsIOS(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('getOsName')
            ->willReturn('iOS');

        $this->assertTrue($this->extension->isIOS());
    }

    public function testIsMacOS(): void
    {
        $this->analyzer->expects($this->once())
            ->method('analyzeCurrentRequest')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('getOsName')
            ->willReturn('macOS');

        $this->assertTrue($this->extension->isMacOS());
    }
}
