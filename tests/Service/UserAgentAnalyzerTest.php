<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Service;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer
 */
class UserAgentAnalyzerTest extends TestCase
{
    private const CHROME_WINDOWS_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';
    private const SAFARI_MACOS_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Safari/605.1.15';
    private const EDGE_WINDOWS_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0';

    private RequestStack $requestStack;

    private ?LoggerInterface $logger;

    private UserAgentAnalyzer $analyzer;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->analyzer = new UserAgentAnalyzer($this->requestStack, $this->logger);
    }

    public function testAnalyzeWithChrome(): void
    {
        $result = $this->analyzer->analyze(self::CHROME_WINDOWS_UA);

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('Windows', $result->getOsName());
        $this->assertEquals('10', $result->getOsVersion());
        $this->assertEquals('Chrome', $result->getBrowserName());
        $this->assertEquals('91.0', $result->getBrowserVersion());
        $this->assertEquals('desktop', $result->getDeviceType());
    }

    public function testAnalyzeWithEdge(): void
    {
        $result = $this->analyzer->analyze(self::EDGE_WINDOWS_UA);

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('Windows', $result->getOsName());
        $this->assertEquals('10', $result->getOsVersion());
        $this->assertEquals('Edge', $result->getBrowserName());
        $this->assertEquals('130.0', $result->getBrowserVersion());
        $this->assertEquals('desktop', $result->getDeviceType());
    }

    public function testAnalyzeWithSafari(): void
    {
        $result = $this->analyzer->analyze(self::SAFARI_MACOS_UA);

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('unknown', $result->getOsName());
        $this->assertEquals('0', $result->getOsVersion());
        $this->assertEquals('Safari', $result->getBrowserName());
        $this->assertEquals('15.0', $result->getBrowserVersion());
        $this->assertEquals('unknown', $result->getDeviceType());
    }

    /**
     * @covers \Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer::analyzeCurrentRequest
     */
    public function testAnalyzeCurrentRequestWithNoRequest(): void
    {
        $result = $this->analyzer->analyzeCurrentRequest();

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('unknown', $result->getOsName());
        $this->assertEquals('unknown', $result->getBrowserName());
        $this->assertEquals('unknown', $result->getDeviceType());
    }

    public function testAnalyzeCurrentRequestWithRequest(): void
    {
        $request = new Request();
        $request->headers->set('User-Agent', self::CHROME_WINDOWS_UA);
        $this->requestStack->push($request);

        $result = $this->analyzer->analyzeCurrentRequest();

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('Windows', $result->getOsName());
        $this->assertEquals('10', $result->getOsVersion());
        $this->assertEquals('Chrome', $result->getBrowserName());
        $this->assertEquals('91.0', $result->getBrowserVersion());
        $this->assertEquals('desktop', $result->getDeviceType());
    }

    /**
     * @covers \Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer::analyzeCurrentRequest
     */
    public function testAnalyzeCurrentRequestWithEmptyUserAgent(): void
    {
        $request = new Request();
        $this->requestStack->push($request);

        $result = $this->analyzer->analyzeCurrentRequest();

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('unknown', $result->getOsName());
        $this->assertEquals('unknown', $result->getBrowserName());
        $this->assertEquals('unknown', $result->getDeviceType());
    }

    public function testAnalyzeWithNullUserAgent(): void
    {
        $result = $this->analyzer->analyze(null);

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('unknown', $result->getOsName());
        $this->assertEquals('unknown', $result->getBrowserName());
        $this->assertEquals('unknown', $result->getDeviceType());
    }

    public function testAnalyzeWithEmptyUserAgent(): void
    {
        $result = $this->analyzer->analyze('');

        $this->assertInstanceOf(UserAgentResult::class, $result);
        $this->assertEquals('unknown', $result->getOsName());
        $this->assertEquals('unknown', $result->getBrowserName());
        $this->assertEquals('unknown', $result->getDeviceType());
    }
}
