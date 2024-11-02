<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Model;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for UserAgentResult model.
 */
#[CoversClass(UserAgentResult::class)]
class UserAgentResultTest extends TestCase
{
    private UserAgentResult $result;

    protected function setUp(): void
    {
        $this->result = new UserAgentResult();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals('unknown', $this->result->getOsType());
        $this->assertEquals('unknown', $this->result->getOsFamily());
        $this->assertEquals('unknown', $this->result->getOsName());
        $this->assertEquals(0, $this->result->getOsVersion());
        $this->assertEquals('unknown', $this->result->getOsTitle());
        $this->assertEquals('unknown', $this->result->getDeviceType());
        $this->assertEquals('unknown', $this->result->getBrowserName());
        $this->assertEquals(0, $this->result->getBrowserVersion());
        $this->assertEquals('unknown', $this->result->getBrowserTitle());
        
        // Test default boolean values
        $this->assertFalse($this->result->isBrowserChromeOriginal());
        $this->assertFalse($this->result->isBrowserFirefoxOriginal());
        $this->assertFalse($this->result->isBrowserSafariOriginal());
        $this->assertFalse($this->result->isBrowserAndroidWebview());
        $this->assertFalse($this->result->isBrowserIosWebview());
        $this->assertFalse($this->result->isBrowserDesktopMode());
        $this->assertFalse($this->result->is64BitsMode());
        $this->assertFalse($this->result->isResultIos());

        // Test default device type checks
        $this->assertFalse($this->result->isMobile());
        $this->assertFalse($this->result->isDesktop());
        $this->assertFalse($this->result->isTablet());
    }

    public function testSettersAndGetters(): void
    {
        $this->result->setOsType('desktop')
            ->setOsFamily('windows')
            ->setOsName('Windows')
            ->setOsVersion(10.0)
            ->setOsTitle('Windows 10')
            ->setDeviceType('desktop')
            ->setBrowserName('Chrome')
            ->setBrowserVersion(120.0)
            ->setBrowserTitle('Chrome 120.0');

        $this->assertEquals('desktop', $this->result->getOsType());
        $this->assertEquals('windows', $this->result->getOsFamily());
        $this->assertEquals('Windows', $this->result->getOsName());
        $this->assertEquals(10.0, $this->result->getOsVersion());
        $this->assertEquals('Windows 10', $this->result->getOsTitle());
        $this->assertEquals('desktop', $this->result->getDeviceType());
        $this->assertEquals('Chrome', $this->result->getBrowserName());
        $this->assertEquals(120.0, $this->result->getBrowserVersion());
        $this->assertEquals('Chrome 120.0', $this->result->getBrowserTitle());
    }

    public function testBooleanSettersAndGetters(): void
    {
        $this->result->setIsBrowserChromeOriginal(true)
            ->setIsBrowserFirefoxOriginal(true)
            ->setIsBrowserSafariOriginal(true)
            ->setIsBrowserAndroidWebview(true)
            ->setIsBrowserIosWebview(true)
            ->setIsBrowserDesktopMode(true)
            ->setIs64BitsMode(true)
            ->setIsResultIos(true);

        $this->assertTrue($this->result->isBrowserChromeOriginal());
        $this->assertTrue($this->result->isBrowserFirefoxOriginal());
        $this->assertTrue($this->result->isBrowserSafariOriginal());
        $this->assertTrue($this->result->isBrowserAndroidWebview());
        $this->assertTrue($this->result->isBrowserIosWebview());
        $this->assertTrue($this->result->isBrowserDesktopMode());
        $this->assertTrue($this->result->is64BitsMode());
        $this->assertTrue($this->result->isResultIos());
    }

    public function testDeviceTypeChecks(): void
    {
        // Test mobile device
        $this->result->setDeviceType('mobile');
        $this->assertTrue($this->result->isMobile());
        $this->assertFalse($this->result->isDesktop());
        $this->assertFalse($this->result->isTablet());

        // Test desktop device
        $this->result->setDeviceType('desktop');
        $this->assertFalse($this->result->isMobile());
        $this->assertTrue($this->result->isDesktop());
        $this->assertFalse($this->result->isTablet());

        // Test tablet device
        $this->result->setDeviceType('tablet');
        $this->assertFalse($this->result->isMobile());
        $this->assertFalse($this->result->isDesktop());
        $this->assertTrue($this->result->isTablet());

        // Test unknown device
        $this->result->setDeviceType('unknown');
        $this->assertFalse($this->result->isMobile());
        $this->assertFalse($this->result->isDesktop());
        $this->assertFalse($this->result->isTablet());
    }

    public function testToArray(): void
    {
        $this->result->setOsType('desktop')
            ->setOsFamily('windows')
            ->setOsName('Windows')
            ->setOsVersion(10.0)
            ->setDeviceType('desktop')
            ->setBrowserName('Chrome')
            ->setBrowserVersion(120.0)
            ->setIsBrowserChromeOriginal(true)
            ->setIsBrowserFirefoxOriginal(false)
            ->setIsBrowserSafariOriginal(false)
            ->setIsBrowserAndroidWebview(false)
            ->setIsBrowserIosWebview(false)
            ->setIsBrowserDesktopMode(true)
            ->setIs64BitsMode(true);

        $array = $this->result->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('desktop', $array['os_type']);
        $this->assertEquals('windows', $array['os_family']);
        $this->assertEquals('Windows', $array['os_name']);
        $this->assertEquals(10.0, $array['os_version']);
        $this->assertEquals('desktop', $array['device_type']);
        $this->assertEquals('Chrome', $array['browser_name']);
        $this->assertEquals(120.0, $array['browser_version']);
        $this->assertTrue($array['is_browser_chrome_original']);
        $this->assertFalse($array['is_browser_firefox_original']);
        $this->assertFalse($array['is_browser_safari_original']);
        $this->assertFalse($array['is_browser_android_webview']);
        $this->assertFalse($array['is_browser_ios_webview']);
        $this->assertTrue($array['is_browser_desktop_mode']);
        $this->assertTrue($array['is_64bits_mode']);
    }
}
